<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class adminBackController extends AbstractController
{


    /**
      * 
      * @var FormationRepository
      */
    private $formationRepository;
 
     /**
      * 
      * @var CategorieRepository
      */
    private $categorieRepository;

     /**
      * 
      * @var CONTROLLER_NAME
      */
    private $CONTROLLER_NAME;

    private $FORMATIONS_TWIG_PATH;
    private $FORMATION_TWIG_PATH;

    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->FORMATIONS_TWIG_PATH = "pages/formations.html.twig";
        $this->FORMATION_TWIG_PATH = "pages/admin_pages/admin_formation.html.twig";
        $this->ADMIN_ACCUEIL_TWIG_PATH = "adminbase.html.twig";
        $this->CONTROLLER_NAME = "adminBackController";
    }


    #[Route('/admin', name: 'admin.accueil')]
    public function index(): Response
    {

        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->ADMIN_ACCUEIL_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }

    #[Route('/admin/formations', name: 'admin.formations')]
    public function formations_page(): Response
    {

        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }

    
    #[Route('/admin/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }     

    #[Route('/admin/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    #[Route('/admin/formation/{id}', name: 'admin.formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render($this->FORMATION_TWIG_PATH, [
            'creating' => false,
            'formation' => $formation,
            'categories' => $this->categorieRepository
        ]);
    }   

    #[Route('/admin/ajouter_formation/', name: 'admin.creer_formations')]
    public function createFormation(): Response{
        // $formation = $this->formationRepository->find($id);
        return $this->render($this->FORMATION_TWIG_PATH, [
            'creating' => true,
            'categories' => $this->categorieRepository
        ]);
    }   




    #[Route('/admin/formations/suppr/{id}', name: 'admin.formations.suppr', methods: ['GET','POST'])]
    public function supprimerFormation(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if (!$formation) {
            $this->addFlash('danger', 'Formation non trouvée.');
            return $this->redirectToRoute('admin.formations');
        }

        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('delete_formation_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.formations');
        }

        $this->formationRepository->remove($formation);

        $this->addFlash('success', 'Formation supprimée avec succès.');

        return $this->redirectToRoute('admin.formations');
    }


}