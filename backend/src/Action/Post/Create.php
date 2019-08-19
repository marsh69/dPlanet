<?php

namespace App\Action\Post;

use App\Entity\Post;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Create
{
    /** @var PostService $postService */
    protected $postService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param PostService $postService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        PostService $postService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->postService = $postService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'post'])
        );
    }

    /**
     * @ParamConverter("post", class="App\Entity\Post", converter="fos_rest.request_body")
     *
     * @SWG\Post(
     *     summary="Add a post",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Post::class, groups={"post"})
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="body", type="string", example="I like this platform a lot!"),
     *             @SWG\Property(property="image", type="string"),
     *         )
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

        $post->setPostedBy($this->security->getUser());

        $this->postService->save($post);

        return $this->view->setData($post)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}