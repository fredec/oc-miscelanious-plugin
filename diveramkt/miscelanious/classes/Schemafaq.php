<?php namespace Diveramkt\Miscelanious\Classes;

use Event;
use Schema;

class Schemafaq {

	public static function events(){
		Event::listen('cms.page.display', function ($controller, $url, $page) {
			ob_start(function ($html) {

				$inicio='<figure '; $fim='</figure>';
				preg_match_all("#".$inicio."(.*?)".$fim."#s", $html, $figures);
				$replace1=[]; $replace2=[];


				if(isset($figures[0][0])){
					foreach ($figures[0] as $key => $value) {
						if(strpos("[".$figures[1][$key]."]",'data-schema-faq="true"')){


							$inicio='data-schema-faq-postid="'; $fim='"';
							preg_match_all("#".$inicio."(.*?)".$fim."#s", $html, $postid);

							$html_faq_replace='';
							if(isset($postid[1][0])){
								$list_faq=\Diveramkt\Miscelanious\Models\Schemafaq::where('post_id',$postid[1][0])->enabled()->get();
								if(isset($list_faq[0]->id)){

									$html_faq_replace .= '<ul class="lista_schema_faq" data-faq>';
									foreach ($list_faq as $item) {
										$html_faq_replace .= '<li>';
										$html_faq_replace .= '<p class="faq-question">'.$item->title.'</p>';
										$html_faq_replace .= '<div class="faq-answer">'.$item->text.'</div>';
										$html_faq_replace .= '</li>';
									}
									$html_faq_replace .= '</ul>';

									$schema = [
										'@context' => 'https://schema.org',
										'@type' => 'FAQPage',
										'mainEntity' => []
									];

									foreach ($list_faq as $item) {
										$schema['mainEntity'][] = [
											'@type' => 'Question',
											'name' => $item->title,
											'acceptedAnswer' => [
												'@type' => 'Answer',
												'text' => strip_tags($item->text)
											]
										];
									}

									$script = '
									<link rel="stylesheet" href="'.url('plugins/diveramkt/miscelanious/assets/css/schemafaq.css').'?v=0.0.1">
									<script src="'.url('plugins/diveramkt/miscelanious/assets/js/schemafaq.js').'" defer="1"></script>
									<script type="application/ld+json">'.json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</script>';
									$html = str_replace('</head>', $script.'</head>', $html);

								}
							}
							array_push($replace1, $value);
							array_push($replace2, $html_faq_replace);
						}
					}
				}

				if(count($replace1)) $html=str_replace($replace1, $replace2, $html);
				return $html;
			});
		});
	}

	public static function extend(){
		\RainLab\Blog\Models\Post::extend(function ($model) {
			$model->hasMany['schemafaq'] = [
				\Diveramkt\Miscelanious\Models\Schemafaq::class,
			];

			$model->bindEvent('model.beforeSave', function () use ($model) {
				// $infos = (array) $model->infos;
				// foreach (array_keys($model->attributes) as $key) {
				// 	if (\Schema::hasColumn($model->getTable(), $key)) continue;
				// 	if (in_array($key, ['id', 'created_at', 'updated_at'])) continue;
				// 	$infos[$key] = $model->attributes[$key];
				// 	unset($model->attributes[$key]);
				// }
				// $model->infos = $infos;

				$inicio='data-schema-faq'; $fim='"';
				preg_match_all("#".$inicio."(.*?)".$fim."#s", $model->content, $check_faq);
				if(isset($check_faq[0][0])){
					$inicio='data-schema-faq-postid'; $fim='"';
					preg_match_all("#".$inicio."(.*?)".$fim."#s", $model->content, $check_faq_post);
					if(!isset($check_faq_post[0][0])){
						$model->content=str_replace('data-schema-faq="true"', 'data-schema-faq="true" data-schema-faq-postid="'.$model->id.'"', $model->content);
					}
				}
			});
		});
		\RainLab\Blog\Controllers\Posts::extend(function ($controller) {

			if (!in_array(\Backend\Behaviors\RelationController::class, $controller->implement)) {
				$controller->implement[] = \Backend\Behaviors\RelationController::class;
			}

			$controller->addDynamicProperty('relationConfig', [
				'schemafaq' => [
					'label' => 'FAQ Interno',
					'view' => [
						'list' => '$/diveramkt/miscelanious/models/schemafaq/columns.yaml',
						'toolbarButtons' => 'create|delete',
						'toolbarPartial' => '$/diveramkt/miscelanious/controllers/schemafaq/_relation_toolbar.htm',
						'defaultSort' => [
							'column' => 'sort_order',
							'direction' => 'desc',
						],
					],
					'manage' => [
						'form' => '$/diveramkt/miscelanious/models/schemafaq/fields.yaml',
					],
				],
			]);

		});
	}

	public static function extendFields($widget){
		if($widget->model instanceof \RainLab\Blog\Models\Post && Schema::hasTable('rainlab_blog_posts')){
			if ($widget->isNested) return;
			$widget->addSecondaryTabFields([
				'schemafaq' => [
					'label'   => 'Faq interno',
					'span' => 'full',
                // 'type' => 'relation',
					'type' => 'partial',
					'path' => '$/diveramkt/miscelanious/controllers/schemafaq/_schemafaq_render.php',
					'tab' => 'Faq interno',
				],
			]);
		}
	}

}