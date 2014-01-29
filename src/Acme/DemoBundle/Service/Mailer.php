<?php

namespace Acme\DemoBundle\Service;

use bicpi\HtmlConverter\Converter\ConverterInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DI\Service("acme.mailer")
 */
class Mailer
{
    private $mailer;

    private $html2text;

    private $templating;

    private $mailerParams;

    /**
     * @DI\InjectParams({
     *     "mailerParams" = @DI\Inject("%mailer_params%")
     * })
     */
    public function __construct(
        \Swift_Mailer $mailer,
        ConverterInterface $html2text,
        EngineInterface $templating,
        array $mailerParams
    ){
        $this->mailer = $mailer;
        $this->html2text = $html2text;
        $this->templating = $templating;
        $this->mailerParams = $mailerParams;
    }

    public function send(\Swift_Message $message, $tplName, array $tplParams = array())
    {
        if (!$message->getFrom()) {
            $message->setFrom($this->mailerParams['from']);
        }

        if (!$message->getReturnPath()) {
            $message->setReturnPath($this->mailerParams['return_path']);
        }

        $html = $this->templating->render($tplName, $tplParams);
        $message
            ->setBody($html, 'text/html')
            ->addPart($this->html2text->convert($html), 'text/plain');

        if (!$message->getSubject()) {
            $message->setSubject((new Crawler($html))->filter('head title')->text());
        }

        $this->mailer->send($message);
    }
}
