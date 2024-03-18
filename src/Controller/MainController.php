<?php

namespace App\Controller;

use App\Entity\Articulo;
use App\Repository\ArticuloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('mostrarArticulo.html.twig', [
            'articulo' => $articulo,
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

    //crearArticulo
    #[Route('/crear_articulo', name: 'crear_articulo')]
    public function crearArticulo(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Check if required parameters are provided
        if (!isset($data['titulo']) || !isset($data['autor']) || !isset($data['contenido']) || !isset($data['categoria'])) {
            return new Response('Missing parameters', Response::HTTP_BAD_REQUEST);
        }

        // Extract parameters from request body
        $titulo = $data['titulo'];
        $autor = $data['autor'];
        $contenido = $data['contenido'];
        $categoria = $data['categoria'];

        $articulo = new Articulo();
        $articulo->setTitulo($titulo);
        $articulo->setAutor($autor);
        $articulo->setContenido($contenido);
        $articulo->setCreado(new \DateTime());
        $articulo->setCategoria($categoria);

        $this->em->persist($articulo);
        $this->em->flush();

        return $this->redirectToRoute('listar_articulos');
    }
}
