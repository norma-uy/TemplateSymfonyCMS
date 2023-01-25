<?php

namespace App\Controller\Admin;

use App\Entity\PostCategory;
use App\Form\Admin\Field\TextareaField;
use App\Repository\PostCategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PostCategoryCrudController extends AbstractCrudController
{
    /**
     * Undocumented function
     *
     * @param Security $security
     */
    public function __construct(private Security $security, private PostCategoryRepository $postCategoryRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return PostCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the visible title at the top of the page and the content of the <title> element
            // it can include these placeholders:
            //   %entity_name%, %entity_as_string%,
            //   %entity_id%, %entity_short_id%
            //   %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Categoría')
            ->setEntityLabelInSingular('Categoría')
            ->setEntityLabelInPlural('Categorías');

        // in DETAIL and EDIT pages, the closure receives the current entity
        // as the first argument
        // ->setPageTitle('detail', fn (Product $product) => (string) $product)
        // ->setPageTitle('edit', fn (Category $category) => sprintf('Editing <b>%s</b>', $category->getName()))

        // the help message displayed to end users (it can contain HTML tags)
        // ->setHelp('edit', '...');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(TextFilter::new('title', 'Título'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('title', 'Título'),
            BooleanField::new('enableMenu', 'Habilitar en el menú'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable()->add(Crud::PAGE_INDEX, Action::DETAIL, Action::NEW, Action::DELETE);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $currentUser = $this->security->getUser();

        if ($currentUser && $entityInstance instanceof PostCategory) {
            $titleSlug = $this->makeSlug($entityInstance);

            $entityInstance->setSlug($titleSlug);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof PostCategory) {
            $titleSlug = $this->makeSlug($entityInstance);

            $entityInstance->setSlug($titleSlug);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    private function makeSlug(PostCategory $entityInstance): string
    {
        $slugger = new AsciiSlugger();

        $titleSlug = $slugger->slug($entityInstance->getTitle())->lower();

        $postByCurrentSlug = $this->postCategoryRepository->findOneBySlug($titleSlug, $entityInstance);

        $titleSlug = $postByCurrentSlug ? "{$titleSlug}-duplicate" : $titleSlug;

        return $titleSlug;
    }
}
