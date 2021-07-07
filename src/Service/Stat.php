<?php

namespace App\Service;
use App\Repository\ProviderRepository;
use App\Repository\ArticleRepository;


class Stat {

    private $providerRepository;
      private $articleRepository;



    public function __construct(ProviderRepository $providerRepository,ArticleRepository $articleRepository)
    {
       $this->providerRepository =  $providerRepository ;
       $this->articleRepository=  $articleRepository ;

    }


    public function nbProviders(): string
    {        
       return $this->providerRepository->totalProviders();
    }

    public function nbArticles(): string
    {  
       return $this->articleRepository->totalArticles();
    }
}