<?php

namespace App\Service;
use App\Repository\ArticleRepository;

class StatArticle {

    private $articleRepository;


    public function __construct(ArticleRepository $articleRepository)
    {
       $this->articleRepository =  $articleRepository ;
    }


    public function nbArticles(): string
    {
        
       return $this->articleRepository->totalArticle();
    }
}