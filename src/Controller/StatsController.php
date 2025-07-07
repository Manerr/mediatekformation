<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Dompdf\Options;
use Dompdf\Dompdf;

final class StatsController extends AbstractController
{


    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;





    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }


    #[Route('/stats', name: 'stats')]
    public function index(): Response
    {
        $nbCategories = $this->categorieRepository->count([]);
        $nbFormations = $this->formationRepository->count([]);

        $categories = $this->categorieRepository->findAllCategories();
        $nbFormationsParCategorie = [];

        foreach ($categories as $categorie) {
            $nbFormationsParCategorie[$categorie->getName()] = $this->categorieRepository
                ->countFormationsForOneCategorie($categorie->getId());
        }

        $categoriesNames = array_keys($nbFormationsParCategorie);
        $formationsCounts = array_values($nbFormationsParCategorie);

        return $this->render('pages/stats.html.twig', [
            'nbCategories' => $nbCategories,
            'nbFormations' => $nbFormations,
            'categoriesNames' => $categoriesNames,
            'formationsCounts' => $formationsCounts,
        ]);
    }


    #[Route('/stats/pdf', name: 'app_stats_pdf')]
    public function statsPdf(): Response
    {
        $categoriesByFormations = $this->categorieRepository->findCategoriesOrderByNbFormationsDesc();




        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);


        $html = $this->renderView('pdf/stats.html.twig', [
            'categoriesByFormations' => $categoriesByFormations,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stats-categories.pdf"',
        ]);
    }



}
