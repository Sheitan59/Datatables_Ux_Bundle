<?php

namespace Habib\DataTables\Twig;

use Habib\DataTables\Model\DataTable;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DataTableExtension extends AbstractExtension
{
    private $stimulus;

    /**
     * @param $stimulus StimulusHelper
     */
    public function __construct(StimulusHelper|StimulusTwigExtension $stimulus)
    {
        if ($stimulus instanceof StimulusTwigExtension) {
            trigger_deprecation('habib/datatables', '1.0', 'Passing an instance of "%s" to "%s" is deprecated, pass an instance of "%s" instead.', StimulusTwigExtension::class, __CLASS__, StimulusHelper::class);
            $stimulus = new StimulusHelper(null);
        }

        $this->stimulus = $stimulus;
    }

    // public function __construct(
    //     private StimulusHelper $stimulus
    // ) {
    // }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_datatable', [$this, 'renderDataTable'], ['is_safe' => ['html']]),
        ];
    }

    public function renderDataTable(DataTable $tables, array $attributes = []): string
    {
        $tables->setAttributes(array_merge($tables->getAttributes(), $attributes));

        $controllers = [];
        if ($tables->getDataController()) {
            $controllers[$tables->getDataController()] = [];
        }
        $controllers['@habib/datatables/datatable'] = ['view' => $tables->getOptions()];

        $stimulusAttributes = $this->stimulus->createStimulusAttributes();
        foreach ($controllers as $name => $controllerValues) {
            $stimulusAttributes->addController($name, $controllerValues);
        }

        foreach ($tables->getAttributes() as $name => $value) {
            if ('data-controller' === $name) {
                continue;
            }

            if (true === $value) {
                $stimulusAttributes->addAttribute($name, $name);
            } elseif (false !== $value) {
                $stimulusAttributes->addAttribute($name, $value);
            }
        }

        return \sprintf('<table id="%s" %s></table>', $tables->getId(),$stimulusAttributes);
    }
}