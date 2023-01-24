<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\Admin\Field\MediaField;
use App\Form\Admin\Field\TextareaField;
use App\Repository\MediaRepository;
use App\Service\ImageOptimizer;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MediaCrudController extends AbstractCrudController
{
    /**
     * Undocumented function
     *
     * @param Security $security
     */
    public function __construct(
        private Security $security,
        private LoggerInterface $manualDevLogger,
        private MediaRepository $mediaRepository,
        private UploaderHelper $helper,
        private ImageOptimizer $imageOptimizer,
        private FilesystemOperator $storageMediaOriginal,
        private FilesystemOperator $storageMedia100w,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the visible title at the top of the page and the content of the <title> element
            // it can include these placeholders:
            //   %entity_name%, %entity_as_string%,
            //   %entity_id%, %entity_short_id%
            //   %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Media')
            ->setEntityLabelInSingular('Media')
            ->setEntityLabelInPlural('Media');

        // in DETAIL and EDIT pages, the closure receives the current entity
        // as the first argument
        // ->setPageTitle('detail', fn (Product $product) => (string) $product)
        // ->setPageTitle('edit', fn (Category $category) => sprintf('Editing <b>%s</b>', $category->getName()))

        // the help message displayed to end users (it can contain HTML tags)
        // ->setHelp('edit', '...');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(TextFilter::new('originalFileName', 'Nombre'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            MediaField::new('originalFile', 'Archivo'),
            TextField::new('title', 'Título'),
            TextareaField::new('altText', 'Texto alternativo')->onlyOnForms(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable()->add(Crud::PAGE_INDEX, Action::DETAIL, Action::NEW, Action::DELETE);
    }

    public function createEntity(string $entityFqcn)
    {
        $media = new Media();
        $media->setCreatedAt(new DateTimeImmutable('now'));
        return $media;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $currentUser = $this->security->getUser();

        if ($currentUser && $entityInstance instanceof Media) {
            $entityInstance->setAuthor($currentUser);

            $titleSlug = $this->makeSlug($entityInstance);

            $entityInstance->setSlug($titleSlug);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Media) {
            $titleSlug = $this->makeSlug($entityInstance);

            // $this->makeImageSizes($entityInstance, $entityManager);

            $entityInstance->setSlug($titleSlug);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    private function makeSlug(Media $entityInstance): string
    {
        $slugger = new AsciiSlugger();

        $titleSlug = $slugger->slug($entityInstance->getTitle())->lower();

        $postByCurrentSlug = $this->mediaRepository->findOneBySlug($titleSlug, $entityInstance);

        $titleSlug = $postByCurrentSlug ? "{$titleSlug}-duplicate" : $titleSlug;

        return $titleSlug;
    }

    private function makeImageSizes(Media $media, EntityManagerInterface $entityManager): Media
    {
        $filesystem = new Filesystem();

        $widthList = [
            '100w' => 100,
            '150w' => 150,
            '300w' => 300,
        ];

        foreach ($widthList as $rKey => $rWidth) {
            $rootProjectPath = getcwd();
            $originalFilePath = $this->helper->asset($media, 'originalFile');
            $filePathParts = pathinfo($rootProjectPath . $originalFilePath);
            $tmpStoragePath = $this->getParameter('tmp_storage_path');

            $targetFileName = "{$filePathParts['filename']}_{$rKey}.{$filePathParts['extension']}";
            $tmpTargetFilePath = "{$rootProjectPath}{$tmpStoragePath}/{$targetFileName}";

            $filesystem->copy($rootProjectPath . $originalFilePath, $tmpTargetFilePath, true);

            $this->imageOptimizer->widthResize($tmpTargetFilePath, $rWidth);

            if ($rKey === '100w') {
                $media->imageFile100w = new UploadedFile($tmpTargetFilePath, $targetFileName);
            } elseif ($rKey === '150w') {
                $media->imageFile150w = new UploadedFile($tmpTargetFilePath, $targetFileName);
            } elseif ($rKey === '300w') {
                $media->imageFile300w = new UploadedFile($tmpTargetFilePath, $targetFileName);
            }

            // @unlink($tmpTargetFilePath);
        }

        $entityManager->persist($media);

        return $media;
    }
}
