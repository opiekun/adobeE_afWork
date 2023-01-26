<?php declare(strict_types=1);

namespace Ecommerce121\PartFinder\Console\Command;

use Amasty\Finder\Api\FinderRepositoryInterface;
use Amasty\Finder\Model\ResourceModel\Value\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Export part finder products CSV to stdout
 */
class Export extends Command
{
    /**
     * id parameter for command line
     */
    private const ID = "id";

    /**
     * @var FinderRepositoryInterface
     */
    private FinderRepositoryInterface $finderRepository;
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @param FinderRepositoryInterface $finderRepository
     * @param Collection $collection
     */
    public function __construct(
        FinderRepositoryInterface $finderRepository,
        Collection $collection
    ) {
        parent::__construct();
        $this->finderRepository = $finderRepository;
        $this->collection = $collection;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('partfinder:export');
        $this->setDescription('Exports part finder collection to stdout');
        $this->addArgument(
            self::ID,
            InputArgument::REQUIRED,
            'Id of the part finder'
        );
        parent::configure();
    }

    /**
     * Execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $finderId = $input->getArgument(self::ID);
        $model = $this->finderRepository->getById($finderId);
        if (!$model->getId()) {
            $output->writeln("Couldn't load part finder id " . $finderId);
            exit;
        }
        $dropdowns = array_map(function($o) { return $o->getName();}, $model->getDropdowns());
        $this->collection->joinAllFor($model);
        $out = fopen('php://output', 'w');
        fputcsv($out, array_merge($dropdowns, ["SKU"]));
        foreach ($this->collection as $row) {
            $csvArr = [];
            foreach ($dropdowns as $key => $val) {
                $csvArr[] = $row->getData('name' . $key);
            }
            $csvArr[] = $row->getData('sku');
            fputcsv($out, $csvArr);
        }
        fclose($out);
    }
}
