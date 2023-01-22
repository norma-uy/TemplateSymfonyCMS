<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\Admin\Field\MediaField;
use App\Repository\MediaRepository;
use App\Service\ImageOptimizer;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Entity\File;
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
        private MediaRepository $mediaRepository,
        private UploaderHelper $helper,
        private ImageOptimizer $imageOptimizer,
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
        return $filters->add(TextFilter::new('fileName', 'Nombre'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            MediaField::new('file', 'Archivo'),
            TextField::new('title', 'Título'),
            TextareaField::new('altText', 'Texto alternativo')->onlyOnForms(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable()
            ->add(
                Crud::PAGE_INDEX,
                Action::DETAIL,
                Action::NEW,
                Action::DELETE,
            );
    }

    public function createEntity(string $entityFqcn)
    {
        $media = new Media();
        $media->setCreatedAt(new DateTimeImmutable('now'));
        return $media;
    }

    public function persistEntity(
        EntityManagerInterface $entityManager,
        $entityInstance,
    ): void {
        $currentUser = $this->security->getUser();

        if ($currentUser && $entityInstance instanceof Media) {
            $entityInstance->setAuthor($currentUser);

            $titleSlug = $this->makeSlug($entityInstance);

            $entityInstance->setSlug($titleSlug);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    public function updateEntity(
        EntityManagerInterface $entityManager,
        $entityInstance,
    ): void {
        if ($entityInstance instanceof Media) {
            $titleSlug = $this->makeSlug($entityInstance);

            $entityInstance->setSlug($titleSlug);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    private function makeSlug(Media $entityInstance): string
    {
        $slugger = new AsciiSlugger();

        $titleSlug = $slugger->slug($entityInstance->getTitle())->lower();

        $postByCurrentSlug = $this->mediaRepository->findOneBySlug(
            $titleSlug,
            $entityInstance,
        );

        $titleSlug = $postByCurrentSlug ? "{$titleSlug}-duplicate" : $titleSlug;

        return $titleSlug;
    }

    private function makeImageSizes(
        Media $media,
        EntityManagerInterface $entityManager,
    ): Media {
        $resolutionList = [
            '100x100' => ['x' => 100, 'y' => 100],
            '150x150' => ['x' => 150, 'y' => 150],
            '300x213' => ['x' => 300, 'y' => 213],
        ];

        $filesystem = new Filesystem();

        foreach ($resolutionList as $rKey => $rValue) {
            $mainFilePath = getcwd() . $this->helper->asset($media, 'file');
            $filePathParts = pathinfo($mainFilePath);

            /* $ancientUmask = umask(0);
            chmod($filePathParts['dirname'], 0755);
            $targetDir = "{$filePathParts['dirname']}/{$rKey}";
            $filesystem->mkdir($targetDir, 0744);
            umask($ancientUmask); */

            $targetDir = "{$filePathParts['dirname']}";

            $targetFileName = "{$filePathParts['filename']}_{$rKey}.{$filePathParts['extension']}";
            $targetFilePath = "{$targetDir}/{$targetFileName}";

            // $filesystem->copy($mainFilePath, $targetFilePath, true);
        }

        $entityManager->persist($media);

        return $media;
    }
}
