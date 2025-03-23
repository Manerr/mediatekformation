<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

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

    private $FORMATIONS_TWIG_PATH;
    private $FORMATION_TWIG_PATH;

    private $CONTROLLER_NAME;

    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
        $this->FORMATIONS_TWIG_PATH = "pages/formations.html.twig";
        $this->FORMATION_TWIG_PATH = "pages/formation.html.twig";
        $this->CONTROLLER_NAME = "FormationsController";
    }
    
    #[Route('/formations', name: 'formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'CONTROLLER_NAME' => $this->CONTROLLER_NAME
        ]);
    }

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'CONTROLLER_NAME' => $this->CONTROLLER_NAME
        ]);
    }     

    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'CONTROLLER_NAME' => $this->CONTROLLER_NAME,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render($this->FORMATION_TWIG_PATH, [
            'formation' => $formation,
            'categories' => $this->categorieRepository
        ]);        
    }   
    
}
