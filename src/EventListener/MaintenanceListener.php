<?php
namespace App\EventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class MaintenanceListener
{
    private $maintenance;
    private $ipAuthorized;
    private $engine;

    /**
     * MaintenanceListener constructor.
     * @param $maintenance
     * @param Environment $engine
     */
    public function __construct($maintenance, Environment $engine)
    {
        $this->maintenance = $maintenance["status"];
        $this->ipAuthorized = $maintenance["ipAuthorized"];
        $this->engine = $engine;
    }


    public function displayMaintenance(RequestEvent $event)
    {
        // If maintenance mode and ip is not authorized, then displaying the right template.
        if($this->maintenance AND !in_array($_SERVER['REMOTE_ADDR'], array_values($this->ipAuthorized))) {
            $err = 'An error occurred defining the EvalBook maintenance state';
            try {
                $template = $this->engine->render('maintenance/index.html.twig');
                // We send our response with a 503 response code (service unavailable)
                $event->setResponse(new Response($template, 503));
            }
            catch (LoaderError $e) {
                throw new NotFoundHttpException($err);
            }
            catch (RuntimeError $e) {
                throw new NotFoundHttpException($err);
            }
            catch (SyntaxError $e) {
                throw new NotFoundHttpException($err);
            }

            $event->stopPropagation();
        }
    }
}