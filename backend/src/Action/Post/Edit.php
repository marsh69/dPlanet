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

class Edit
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
     * @ParamConverter("newPost", class="App\Entity\Post", converter="fos_rest.request_body")
     *
     * @SWG\Put(
     *     summary="Edit a post",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Post::class, groups={"post"})
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden",
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
     *         )
     *     ),
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Post $post
     * @param Post $newPost
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Post $post, Post $newPost, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $post)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $post->setBody($newPost->getBody());

        $this->postService->save($post);

        return $this->view->setData($post);
    }
}
