<?php

namespace bundle\UploaderBundle;

use App\FdsDisk\FdsStreamWrapper;
use Symfony\Bundle\DebugBundle\DependencyInjection\Compiler\DumpDataCollectorPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UploaderBundle extends Bundle
{
    final public function register():void
    {
        $wrap = new FdsStreamWrapper();
    }

    public function __construct()
    {
        $this->register();
    }

    final public function boot(): void
    {
        $_ENV['DOCTRINE_DEPRECATIONS'] = $_SERVER['DOCTRINE_DEPRECATIONS'] ??= 'trigger';

        $handler = ErrorHandler::register(null, false);

        $this->container->get('debug.error_handler_configurator')->configure($handler);

        if ($this->container->getParameter('kernel.http_method_override')) {
            Request::enableHttpMethodParameterOverride();
        }

        if ($this->container->hasParameter('kernel.trust_x_sendfile_type_header') && $this->container->getParameter('kernel.trust_x_sendfile_type_header')) {
            BinaryFileResponse::trustXSendfileTypeHeader();
        }
    }
    final public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        //$container->addCompilerPass(new DumpDataCollectorPass());
    }

    final public function registerCommands(Application $application): void
    {
        // noop
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
