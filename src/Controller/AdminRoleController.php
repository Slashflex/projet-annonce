<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class AdminRoleController extends AbstractController
{
    /**
     * @Route("/role", name="admin_role")
     */
    public function index(RoleRepository $roleRepo)
    {
        $roles = $roleRepo->findAll();

        return $this->render('admin/role/index.html.twig', [
            'roles' => $roles
        ]);
    }


    /**
     * @Route("/roles/ajouter", name="admin_ajouter_role")
     */
    public function ajouterRole(Request $request, EntityManagerInterface $manager)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($role);
            $manager->flush();

            return $this->redirectToRoute('admin_role');
        }

        return $this->render('admin/role/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/roles/{id}", name="admin_editer_roles")
     */
    public function editerRole(Role $role, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($role);
            $manager->flush();

            return $this->redirectToRoute('admin_role');
        }

        return $this->render('admin/role/editer-role.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
