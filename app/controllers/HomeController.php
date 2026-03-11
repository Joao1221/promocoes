<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();

        $this->render('home/index', [
            'title' => 'Shopping virtual de Capela-SE',
            'categories' => (new Category())->all(),
            'promotions' => $productModel->homePromotions(),
            'featuredProducts' => $productModel->homeShowcase(12),
            'featuredStores' => (new Store())->featured(),
            'nearbyStores' => (new Store())->featured(4),
        ]);
    }
}
