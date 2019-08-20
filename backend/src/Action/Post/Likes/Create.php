<?php

namespace App\Action\Post\Likes;

use App\Entity\Like;
use App\Entity\Post;
use App\Service\LikeService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Create
{
    /** @var LikeService $likeService */
    protected $likeService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param LikeService $likeService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        LikeService $likeService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->likeService = $likeService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'like'])
        );
    }

    /**
     * @ParamConverter("like", class="App\Entity\Like", converter="fos_rest.request_body")
     * @SWG\Post(
     *     summary="Add a like to a post",
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Post $post
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Post $post, ConstraintViolationListInterface $violationList): View
    {
        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $user = $this->security->getUser();

        $existingLike = $this->likeService->findOneBy(['post' => $post, 'developer' => $user]);

        if ($existingLike) {
            return $this->view->setStatusCode(Response::HTTP_CONFLICT)
                ->setData(['message' => 'Like already exists!']);
        }


        $like = (new Like())
            ->setDeveloper($this->security->getUser())
            ->setPost($post);

        $this->likeService->save($like);


        return $this->view->setData($like)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
