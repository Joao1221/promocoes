<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index', [
            'title' => 'Shopping virtual de Capela-SE',
            'categories' => (new Category())->all(),
            'promotions' => (new Product())->homePromotions(),
            'featuredProducts' => (new Product())->featured(),
            'featuredStores' => (new Store())->featured(),
            'nearbyStores' => (new Store())->featured(4),
        ]);
    }
}
