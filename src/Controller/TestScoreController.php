<?php

namespace App\Controller;

use App\Form\TestResultUploadType;
use App\Service\TestScore\TestScoreService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test-score')]
class TestScoreController extends AbstractController {
    #[Route('/', name: 'homepage')]
    public function index(Request $request, TestScoreService $testScoreService, LoggerInterface $logger): Response {
        $form = $this->createForm(TestResultUploadType::class);
        $form->handleRequest($request);
        
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('TestScore/index.html.twig', [
                'form' => $form->createView(),
                'stats' => [],
            ]);
        }
        
        $file = $form->get('file')->getData();
        
        try {
            $stats = $testScoreService->getStatsFromFilePath($file->getRealPath());
        } catch (FileException $e) {
            $logger->error('File processing error: ' . $e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', 'There was an error processing the file. Please try again.');
            
            return $this->redirectToRoute('homepage');
        }
        
        // Recreate the form to reset its state.
        $form = $this->createForm(TestResultUploadType::class);
        
        return $this->render('TestScore/index.html.twig', [
            'form' => $form->createView(),
            'stats' => $stats,
        ]);
    }
}
