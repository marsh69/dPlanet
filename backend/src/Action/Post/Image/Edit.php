<?php

namespace App\Action\Post\Image;

use App\Entity\Image;
use App\Entity\Post;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Edit
{
    /** @var PostService $postService */
    protected $postService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;
    /** @var ValidatorInterface $validator */
    protected $validator;

    /**
     * Edit constructor.
     * @param PostService $postService
     * @param Context $context
     * @param View $view
     * @param Security $security
     * @param ValidatorInterface $validator
     */
    public function __construct(
        PostService $postService,
        Context $context,
        View $view,
        Security $security,
        ValidatorInterface $validator
    ) {
        $this->postService = $postService;
        $this->view = $view;
        $this->security = $security;
        $this->validator = $validator;

        $this->view->setContext(
            $context->addGroups(['default', 'post'])
        );
    }

    /**
     * @SWG\Post(
     *     summary="Add/Set an image for a post",
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
     *         name="resource",
     *         in="formData",
     *         type="file",
     *         description="Image file",
     *         required=true
     *     ),
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Request $request
     * @param Post $post
     * @return View
     * @throws \Exception
     */
    public function __invoke(Request $request, Post $post): View
    {
        if (!$this->security->isGranted('edit', $post)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        $image = (new Image())
            ->setResource($request->files->get('resource'));

        $validationErrors = $this->validator->validate($image);

        if ($validationErrors->count() > 0) {
            return $this->view->setData($validationErrors)
                ->setStatusCode(400);
        }

        $post->setImage($image);

        $this->postService->save($post);

        return $this->view->setData($post);
    }
}
