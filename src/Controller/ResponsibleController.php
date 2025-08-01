<?php

namespace App\Controller;

use App\Entity\Responsible;
use App\Form\ResponsibleType;
use App\Repository\ResponsibleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/responsible')]
final class ResponsibleController extends AbstractController
{
    #[Route(name: 'app_responsible_index', methods: ['GET'])]
    public function index(ResponsibleRepository $responsibleRepository): Response
    {
        return $this->render('responsible/index.html.twig', [
            'responsibles' => $responsibleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_responsible_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $responsible = new Responsible();
        $form = $this->createForm(ResponsibleType::class, $responsible);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($responsible);
            $entityManager->flush();

            return $this->redirectToRoute('app_responsible_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('responsible/new.html.twig', [
            'responsible' => $responsible,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_responsible_show', methods: ['GET'])]
    public function show(Responsible $responsible): Response
    {
        return $this->render('responsible/show.html.twig', [
            'responsible' => $responsible,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_responsible_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Responsible $responsible, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResponsibleType::class, $responsible);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_responsible_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('responsible/edit.html.twig', [
            'responsible' => $responsible,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_responsible_delete', methods: ['POST'])]
    public function delete(Request $request, Responsible $responsible, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$responsible->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($responsible);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_responsible_index', [], Response::HTTP_SEE_OTHER);
    }
}
