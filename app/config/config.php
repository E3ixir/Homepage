<?php
return array(
		"siteUrl"=>"http://localhost/homepage/",
		"database"=>[
		        "type"=>"mysql",
				"dbName"=>"homepage",
				"serverName"=>"localhost",
				"port"=>"3306",
				"user"=>"root",
				"password"=>"",
				"cache"=>false
		],
		"sessionToken"=>"%temporaryToken%",
		"namespaces"=>[],
		"templateEngine"=>'micro\views\engine\Twig',
		"templateEngineOptions"=>array("cache"=>false),
		"test"=>false,
		"debug"=>true,
		"di"=>["jquery"=>function(){
							$jquery=new Ajax\php\micro\JsUtils(["defer"=>true]);
							$jquery->semantic(new Ajax\Semantic());
							return $jquery;
						}],
		"cacheDirectory"=>"cache/",
		"mvcNS"=>["models"=>"models","controllers"=>"controllers"]
);
