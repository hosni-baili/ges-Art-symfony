<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Form\ProviderType;
use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/provider")
 */
class ProviderController extends AbstractController
{
    /**
     * @Route("/", name="provider_index", methods={"GET"})
     */
    public function index(ProviderRepository $providerRepository): Response
    {
       // $nbreProducts = $providerRepository->totalProviders();
        return $this->render('provider/index.html.twig', [
            'providers' => $providerRepository->findAll(),
            // 'total'=>$nbreProducts,
        ]);
    }

    private function serializeProgrammer(Provider $provider)
    {
        return array(
            'name' => $provider->getName(),
            'email' => $provider->getEmail(),
            'adress' => $provider->getAdress(),
            'id' => $provider->getId(),
            'photo' => $provider->getPhoto(),
        );
    }


        /**
     * @Route("/listajax", name="listajax", methods={"GET"})
     */
    public function indexajax(ProviderRepository $providerRepository): Response
    {  $providers=$providerRepository->findAll();

        $data = array('providers' => array());
        foreach ($providers as $provider) {
            $data['providers'][] = $this->serializeProgrammer($provider);
        }
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }




    /**
     * @Route("/new", name="provider_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // partie gestion des deux fichiers 
            
            $photoFile = $form->get('photo')->getData(); 
            
            if ($photoFile) {
                $originalphotoFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                // $safePhotoFilename = transliterator_transliterate('AnyLatin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalphotoFilename);
                 $newPhotoFilename = $originalphotoFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                 // Move the file to the directory where brochures are stored
            try {
                $photoFile->move($this->getParameter('providers_directory'), $newPhotoFilename);
            
                } catch (FileException $e) {
                
                }
            $provider->setPhoto($newPhotoFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($provider);
            $entityManager->flush();

            return $this->redirectToRoute('provider_index');
        }

        return $this->render('provider/new.html.twig', [
            'provider' => $provider,
            'form' => $form->createView(),
            
        ]);
    }

    /// Fonction d'ajout bas??e sur Ajax

    
    /**
     * @Route("/ajaxAdd", name="addAjaxProvider", methods={"GET","POST"})
     */
    public function addAjaxProvider(Request $request) : Response{

        if($request->getMethod()=="POST")
          {
              $name = $request->get('name');
              $email = $request->get('email');
              $adress = $request->get('adress');
              $provider = new Provider();
    
              $provider->setName($name);
              $provider->setEmail($email);
              $provider->setAdress($adress);
    
              //$provider->setCode(1000);
              $provider->setPhoto("profile.png");
    
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($provider);
              $entityManager->flush();
    
              $response = new Response(json_encode(array(
                  'name' => $provider->getName(),
                  'email' => $provider->getEmail(),
                  'adress' => $provider->getAdress(),
                  'id' => $provider->getId(),
                )));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
    
              //return $this->redirectToRoute('provider_index');
          }
          
    
            return $this->render('provider/ajout.html.twig');
            //return new Response("Ok");
        }
    

    /**
     * @Route("/{id}", name="provider_show", methods={"GET"})
     */
    public function show(Provider $provider): Response
    {
        return $this->render('provider/show.html.twig', [
            'provider' => $provider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="provider_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Provider $provider): Response
    {
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('provider_index');
        }

        return $this->render('provider/edit.html.twig', [
            'provider' => $provider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="provider_delete", methods={"POST"})
     */
    public function delete(Request $request, Provider $provider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$provider->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($provider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('provider_index');
    }
}
