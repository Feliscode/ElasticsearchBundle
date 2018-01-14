<?php

namespace Feliscode\ElasticsearchBundle\Command;

use Elastica\Index;
use Elastica\IndexTemplate;
use Feliscode\ElasticsearchBundle\Client\Client;
use Feliscode\ElasticsearchBundle\Registry\ClientRegistry;
use Feliscode\ElasticsearchBundle\Registry\IndexRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class InstallCommand extends ContainerAwareCommand
{
    const OPTION_UPDATE_TEMPLATES = 'updateTemplates';

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @var IndexRegistry
     */
    private $indexRegistry;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('feliscode:elasticsearch:install')
            ->setDescription('Install indexes with mapping.')
            ->setHelp('This command allows you to install new templates and indexes if they do not exist on your Elastic Search')

            ->addOption(self::OPTION_UPDATE_TEMPLATES, null, InputOption::VALUE_OPTIONAL, 'Update templates', false)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
        $this->clientRegistry = $this->getContainer()->get('feliscode_elasticsearch.registry.client');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->clientRegistry as $clientName => $client) {
            $this->installTemplates($client);
            $this->installIndexes($client);
        }

        $output->writeln('<info>Done</info>');

        return 0;
    }

    /**
     * @param Client $client
     * @return void
     */
    private function installTemplates(Client $client): void
    {
        $templateList = $this->getContainer()->getParameter(
            sprintf('feliscode_elasticsearch.%s.templates', $client->getName())
        );
        if (empty($templateList)) {
            return;
        }
        $this->output->writeln(sprintf('Install templates for client %s...', $client->getName()));

        foreach ($templateList as $templateName => $templateFilePath) {
            if (false === file_exists($templateFilePath)) {
                throw new RuntimeException(sprintf('File %s does not exist.', $templateFilePath));
            }

            $template = new IndexTemplate($client, $templateName);

            $templateExist = $template->exists();
            if ($templateExist && false === $this->input->getOption(self::OPTION_UPDATE_TEMPLATES)) {
                continue;
            }
            if ($templateExist) {
                $template->delete();
            }

            $templateData = json_decode(file_get_contents($templateFilePath), true);
            if (is_null($templateData)) {
                throw new RuntimeException(sprintf('File %s has invalid json.', $templateFilePath));
            }

            $template->create($templateData);
        }
    }

    /**
     * @param Client $client
     * @return void
     */
    public function installIndexes(Client $client): void
    {
        $this->output->writeln(sprintf('Install indexes for client %s...', $client->getName()));

        foreach ($this->indexRegistry as $index) {
            if (false === $index->exists()) {
                $index->create();
            }
        }
    }
}