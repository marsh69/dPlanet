<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Service\DeveloperService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class DeveloperController
{
    /** @var DeveloperService $developerService */
    protected $developerService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * DeveloperController constructor.
     * @param View $view
     * @param DeveloperService $developerService
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        View $view,
        DeveloperService $developerService,
        Context $context,
        Security $security
    ) {
        $this->view = $view;
        $this->developerService = $developerService;
        $this->security = $security;

        $this->view->setContext(
            $context->setGroups(['default', 'developer'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all developers",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Developer::class, groups={"developer"})
     *    )
     * )
     *
     * @return View
     */
    public function index(): View
    {
        if (!$this->security->isGranted('list', new Developer())) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $developers = $this->developerService->findAll();

        return $this->view->setData($developers);
    }

    /**
     * @SWG\Get(
     *     summary="Get a developer",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Developer::class, groups={"developer"})
     *    )
     * )
     *
     * @param Developer $developer
     * @return View
     */
    public function show(Developer $developer): View
    {
        if (!$this->security->isGranted('show', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $this->view->setData($developer);
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
     *
     * @param Developer $developer
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function create(Developer $developer, ConstraintViolationListInterface $violationList): View
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

    /**
     * @ParamConverter("newDeveloper", class="App\Entity\Developer", converter="fos_rest.request_body")
     * @SWG\Put(
     *     summary="Edit an existing developer",
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
     *
     * @param Developer $developer
     * @param Developer $newDeveloper
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function edit(Developer $developer, Developer $newDeveloper, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $developer->setFirstName($newDeveloper->getFirstName())
            ->setLastName($newDeveloper->getLastName())
            ->setEmail($newDeveloper->getEmail())
            ->setPlainPassword($newDeveloper->getPlainPassword());

        $this->developerService->save($developer);

        return $this->view->setData($developer)
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @SWG\Delete(
     *     summary="Delete a developer",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Developer::class, groups={"developer"})
     *    )
     * )
     *
     * @param Developer $developer
     * @return View
     */
    public function delete(Developer $developer): View
    {
        if (!$this->security->isGranted('delete', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        $this->security->isGranted('delete', $developer);

        $this->developerService->delete($developer);

        return $this->view->setData($developer)
            ->setStatusCode(Response::HTTP_OK);
    }
}
