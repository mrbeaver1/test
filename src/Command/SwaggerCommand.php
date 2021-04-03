<?php

namespace App\Command;

use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function OpenApi\scan;

class SwaggerCommand extends Command
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var array
     */
    private $scanOptions;

    /**
     * @var string
     */
    private $controllerDir;

    private const PATH_TO_API_DOC_FILE = 'public/openapi.yaml';

    /**
     * @param string        $projectDir
     * @param string | null $name
     */
    public function __construct(string $projectDir, $name = null)
    {
        parent::__construct($name);

        $this->projectDir = $projectDir;
        $this->scanOptions = [
            'exclude' => [
                'bin',
                'test',
                'var',
                'vendor',
                'public',
            ],
        ];
        $this->controllerDir = 'src/Controller';
    }

    /**
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('swagger')->setDescription('Генерирует файл swagger.json');
    }

    /**
     * @OA\Schema(
     *     @OA\Property(type="array", property="data", @OA\Items(ref="#/components/schemas/errorObject")),
     *     type="object",
     *     schema="error"
     * )
     * @OA\Schema(
     *     @OA\Property(type="array", property="errors", @OA\Items(ref="#/components/schemas/errorBody")),
     *     type="object",
     *     schema="errorObject"
     * )
     * @OA\Schema(
     *     schema="errorBody",
     *     @OA\Property(type="string", property="code", example="string"),
     *     @OA\Property(type="string", property="message", example="string"),
     *     @OA\Property(type="string", property="field", example="string"),
     * )
     * @OA\SecurityScheme(
     *   securityScheme="Bearer",
     *   type="apiKey",
     *   in="header",
     *   name="Authorization"
     * )
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->generateSwagger(
            $output,
            self::PATH_TO_API_DOC_FILE,
            [
                "$this->controllerDir/ApiController.php",
            ]
        );

        return 1;
    }

    /**
     * @param OutputInterface $output
     * @param string          $pathToDocFile
     * @param array           $exclude
     *
     * @throws Exception
     */
    private function generateSwagger(
        OutputInterface $output,
        string $pathToDocFile,
        array $exclude = []
    ): void {
        $scanOptions['exclude'] = array_merge($this->scanOptions['exclude'], $exclude);

        scan($this->projectDir, $scanOptions)->saveAs("$this->projectDir/$pathToDocFile");

        $output->writeln("Файл $pathToDocFile успешно сгенерирован");
    }
}
