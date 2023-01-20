<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PizzaController extends AbstractController
{
    /**
     * @Route("/pizza", name="app_pizza_newPizza" )
     */
    public function newPizza(PizzaRepository $pizzaRepository): Response
    {
        //instantiation d'une pizza
        $pizza = new Pizza;
        $pizza->setName('margarita');
        $pizza->setPrice(15.00);

        // le repo va l'ajouter à la base de données
        $pizzaRepository->add($pizza ,true);

        //response va afficher le msg passé en paramétre
        return new Response("La pizza avec l'id {$pizza->getId()} à bien été enregistré");
    }

    /**
     * @Route("/pizza/generate" , name="app_pizza_generate")
     */
    public function generate(PizzaRepository $Repository): Response{
        //instantiation d'une pizza
        $pizza = new Pizza;
        $pizza->setName('calzon');
        $pizza->setPrice(18.00);


        $Repository->add($pizza, true);
        return new Response("ok {$pizza->getId()} ");
    }
    /**
     * @Route("/pizza/{id}/show" , name="app_pizza_show")
     */
    public function show(Pizza $pizza): Response{

        return new Response("ok {$pizza->getName()} ");

    }


    /**
     * @Route("/pizza/list" , name="app_pizza_list")
     */
    public function list (PizzaRepository $repository):Response{
        //recuperer la liste des pizza depuis la base de données
        $liste= $repository->findAll(); //retourne une liste d'objets pizza

        // Retourne une instance de Response avec le contenue html
        // de notre template
        return $this->render('pizza/list.html.twig', [
        // Définie les variables accessible dans notre template
        // twig
            'liste' => $liste
        ]);

    }



    /**
     * @Route("/pizza/nouvelle" , name="app_pizza_nouvelle")
     */
    public function nouvelle (PizzaRepository $repository, Request $request):Response{
        // 1. Récupérer les champs du formulaire

        //si le formulaire est envoyé
        if($request->isMethod('post')){
            //récupere les champs du formulaire:
            $name= $request->request->get('name');
            $price= $request->request->get('price');
            $description= $request->request->get('description');
            $imageUrl= $request->request->get('imageUrl');
            

            //2. Créer une entité pizza et remplir l'entité avec les champs du formulaire

            $pizza= new Pizza();
            $pizza->setName($name);
            $pizza->setPrice($price);
            $pizza->setDescription($description);
            $pizza->setImageUrl($imageUrl);

             // 3.Utiliser le `PizzaRepository` afin d'enregistrer la pizza dans la base de données
             $repository->add($pizza, true);


               // 4. Si tout c'est bien passé : Rediriger vers la page de la liste des pizzas
               return $this->redirectToRoute('app_pizza_list');
        }


        return $this->render('pizza/newPizza.html.twig');
    }




    /**
     * @Route("/pizza/{id}/modifier" , name="app_pizza_update")
     */
    public function update (int $id, PizzaRepository $repository, Request $request):Response
    {
        //recuperer la pizza avec l'id reçu
        $pizza= $repository->find($id);


        // 1. Tester si le formulaire à été envoyé
        if ($request->isMethod('post')) {

            // 2. Récupérer les données du formulaire
                $name=$request->request->get('name');
                $price= $request->request->get('price');
                $description= $request->request->get('description');
                $imageUrl= $request->request->get('imageUrl');


            // 3. Modifier la pizza avec les données du formulaire
                $pizza->setName($name);
                $pizza->setPrice($price);
                $pizza->setDescription($description);
                $pizza->setImageUrl($imageUrl);


            // 4. Utiliser le repository afin d'enregistrer la pizza en base de données
                $repository->add($pizza, true); // le true c'est pour mettre à jour la base de données directement


            // 5. Si tout c'est bien passé, rediriger vers la liste des pizzas
                return $this->redirectToRoute('app_pizza_list');
        }

        return $this->render('pizza/updatePizza.html.twig', [
            'pizza'=> $pizza,
        ]);
    }

    /**
     * @Route("/pizza/{id}/supprimer" , name="app_pizza_remove")
     */
    public function remove (int $id, PizzaRepository $repository, Request $request):Response
    {

        //récuperer le pizza depuis la base de données
        $pizza= $repository->find($id);

        //je supprime la pizza
        $repository->remove($pizza , true);

        //redirection vers la liste des pizzas
        return $this->redirectToRoute('app_pizza_list');

    }




}
