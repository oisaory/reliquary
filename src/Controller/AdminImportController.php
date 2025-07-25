<?php

namespace App\Controller;

use App\Command\ImportSaintsCommand;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;
use SensioLabs\AnsiConverter\Theme\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller for running import commands from the admin interface
 */
#[Route('/admin/import')]
#[IsGranted('ROLE_ADMIN')]
class AdminImportController extends AbstractController
{
    private ImportSaintsCommand $importSaintsCommand;

    public function __construct(ImportSaintsCommand $importSaintsCommand)
    {
        $this->importSaintsCommand = $importSaintsCommand;
    }

    /**
     * Shows the import saints form and handles the import process
     */
    #[Route('/saints', name: 'app_admin_import_saints')]
    public function importSaints(Request $request): Response
    {
        $output = null;
        $success = null;
        $options = [
            'limit' => null,
            'batch-size' => 50,
            'reset-sequence' => false,
            'dry-run' => false,
        ];

        if ($request->isMethod('POST')) {
            // Get options from the form
            $options['limit'] = $request->request->get('limit') ? (int)$request->request->get('limit') : null;
            $options['batch-size'] = (int)$request->request->get('batch_size', 50);
            $options['reset-sequence'] = $request->request->getBoolean('reset_sequence', false);
            $options['dry-run'] = $request->request->getBoolean('dry_run', false);

            // Set up the command input
            $input = new ArrayInput([
                '--limit' => $options['limit'],
                '--batch-size' => $options['batch-size'],
                '--reset-sequence' => $options['reset-sequence'],
                '--dry-run' => $options['dry-run'],
            ]);

            // Capture the command output with maximum verbosity
            $outputBuffer = new BufferedOutput(OutputInterface::VERBOSITY_DEBUG, true);

            // Run the command
            $returnCode = $this->importSaintsCommand->run($input, $outputBuffer);
            $success = ($returnCode === 0);

            // Get the output content
            $output = $outputBuffer->fetch();
            $converter = new AnsiToHtmlConverter(new class extends Theme {
                public function asArray(): array { return [...parent::asArray(), 'black' => '#2b2b2b']; }
            });
            $output = $converter->convert($output);

            // Debug output to console
            error_log("Command output: " . $output);
            
            // Debug: Add output to flash message to ensure it's being captured
            if (empty($output)) {
                $this->addFlash('warning', 'No output was captured from the command.');
            }

            // Add a flash message
            if ($success) {
                $this->addFlash('success', 'Saints imported successfully!');
            } else {
                $this->addFlash('danger', 'Error importing saints. Check the output for details.');
            }
        }

        return $this->render('admin/import/saints.html.twig', [
            'output' => $output,
            'success' => $success,
            'options' => $options,
        ]);
    }
}