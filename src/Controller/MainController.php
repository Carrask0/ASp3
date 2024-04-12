<?php

namespace App\Controller;

use App\Entity\Articulo;
use App\Entity\Comentario;
use App\Repository\ArticuloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticuloType;
use App\Form\ComentarioType;

class MainController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    //listarArticulos
    #[Route('/articulos', name: 'listar_articulos')]
    public function articulos(): Response
    {
        $repository = $this->em->getRepository(Articulo::class);
        $articulos = $repository->findAll();

        return $this->render('listarArticulos.html.twig', [
            'articulos' => $articulos,
        ]);
    }

    //Formulario para crear articulo
    #[Route('/crear_articulo', name: 'crear_articulo')]
    public function crearArticuloForm(Request $request): Response
    {
        $articulo = new Articulo();
        $form = $this->createForm(ArticuloType::class, $articulo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($articulo);
            $this->em->flush();
            return $this->redirectToRoute('listar_articulos');
        }
        return $this->render('form_base.html.twig', [
            'formulario' => $form->createView()
        ]);
    }

    //Formulario para crear comentario
    #[Route('/crear_comentario/{id}', name: 'crear_comentario')]
    public function crearComentarioForm(Request $request, $id): Response
    {
        $articulo = $this->em->getRepository(Articulo::class)->find($id);

        if (!$articulo) {
            throw $this->createNotFoundException('No se encontró el artículo con el ID: ' . $id);
        }

        $comentario = new Comentario();
        $comentario->setArticuloId($articulo); // Associate the comment with the article

        $form = $this->createForm(ComentarioType::class, $comentario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($comentario);
            $this->em->flush();
            return $this->redirectToRoute('mostrar_articulo', ['id' => $id]);
        }

        return $this->render('form_base.html.twig', [
            'formulario' => $form->createView()
        ]);
    }


    //modificar_titulo
    #[Route('/modificar_titulo', name: 'modificar_titulo')]
    public function modificarTitulo(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        // Check if required parameters are provided
        if (!isset($data['id']) || !isset($data['newTitulo'])) {
            return new Response('Missing parameters', Response::HTTP_BAD_REQUEST);
        }

        // Extract parameters from request body
        $id = $data['id'];
        $newTitulo = $data['newTitulo'];

        $articulo = $em->getRepository(Articulo::class)->find($id);

        // Check if the entity exists
        if (!$articulo) {
            throw $this->createNotFoundException('Articulo not found with id: ' . $id);
        }
        $articulo->setTitulo($newTitulo);
        $em->flush();

        return $this->redirectToRoute('listar_articulos');
    }

    //mostrarArticulo
    #[Route('/articulo/{id}', name: 'mostrar_articulo')]
    public function mostrarArticulo(ArticuloRepository $articuloRepository, $id): Response
    {
        $articulo = $articuloRepository->find($id);

        //Comentarios
        $comentarios = $articulo->getComentarios();

        return $this->render('mostrarArticulo.html.twig', [
            'articulo' => $articulo,
            'comentarios' => $comentarios
        ]);
    }

    //eliminarArticulo
    #[Route('/eliminar_articulo/{id}', name: 'eliminar_articulo')]
    public function eliminarArticulo($id): Response
    {
        $articulo = $this->em->getRepository(Articulo::class)->find($id);

        if (!$articulo) {
            throw $this->createNotFoundException('Articulo not found with id: ' . $id);
        }

        $this->em->remove($articulo);
        $this->em->flush();

        return $this->redirectToRoute('listar_articulos');
    }
}
