<?php

namespace App\Action\Developer;

use App\Entity\Developer;
use App\Service\DeveloperService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Create
{
    /** @var DeveloperService $developerService */
    protected $developerService;
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param DeveloperService $developerService
     * @param View $view
     * @param Context $context
     */
    public function __construct(
        DeveloperService $developerService,
        View $view,
        Context $context
    ) {
        $this->developerService = $developerService;
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'developer'])
        );
    }

    /**
     * @ParamConverter("developer", class="App\Entity\Developer", converter="fos_rest.request_body")
     *
     * @SWG\Post(
     *     security={},
     *     summary="Register a new developer",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="firstName", type="string", example="John"),
     *             @SWG\Property(property="lastName", type="string", example="Doe"),
     *             @SWG\Property(property="username", type="string", example="TheLegend27"),
     *             @SWG\Property(property="password", type="string", example="TheMyth28"),
     *             @SWG\Property(property="email", type="string", example="thelegend27@gmail.com"),
     *             @SWG\Property(property="profileImage", type="file"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Developer::class, groups={"developer"})
     *    )
     * )
     * @SWG\Tag(name="Developer")
     *
     * @param Developer $developer
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Developer $developer, ConstraintViolationListInterface $violationList): View
    {
        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $this->developerService->save($developer);

        return $this->view->setData($developer)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
