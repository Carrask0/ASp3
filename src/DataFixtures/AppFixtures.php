<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Articulo;
use App\Entity\Comentario;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //ARTICULOS
        $articulo = new Articulo();
        $articulo->setTitulo('Articulo 1');
        $articulo->setAutor('Autor 1');
        $articulo->setContenido('Contenido del articulo 1');
        $articulo->setCreado(new \DateTime());
        $articulo->setCategoria('Categoria 1');
        $manager->persist($articulo);

        $articulo2 = new Articulo();
        $articulo2->setTitulo('Articulo 2');
        $articulo2->setAutor('Autor 2');
        $articulo2->setContenido('Contenido del articulo 2');
        $articulo2->setCreado(new \DateTime());
        $articulo2->setCategoria('Categoria 2');
        $manager->persist($articulo2);

        //COMENTARIOS
        $comentario = new Comentario();
        $comentario->setAutor('Autor 1');
        $comentario->setContenido('Contenido del comentario 1');
        $comentario->setRespuesta(1);
        $manager->persist($comentario);

        $comentario2 = new Comentario();
        $comentario2->setAutor('Autor 2');
        $comentario2->setContenido('Contenido del comentario 2');
        $comentario2->setRespuesta(2);
        $manager->persist($comentario2);

        //LINK
        $articulo->addComentario($comentario);
        $articulo2->addComentario($comentario2);

        $manager->flush();
    }
}
