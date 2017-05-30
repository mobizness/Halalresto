<?php 
echo $this->Form->create('Translation'); ?>
	<h5 class="note note-info bold"> Language </h5> <?php
	/*echo $this->Html->link('Add',
							array('action' => 'add'),
							array('Class'=>'btn green btn-sm'));*/ ?>
	<div class="tabbable-custom ">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#HomePage">Page d’accueil</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#OrderPage">Page des commandes</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#ProfilePage">profil</a>
			</li>
		</ul>
		<div class="tab-content"> <?php
			$main = '';
			foreach ($translations as $key => $translation):
				$nextValue = $key+1;
				 if ($translation['Translation']['location'] != $main) { 
				 	$main = $translation['Translation']['location']; ?>


					<div id="<?php echo str_replace(" ","", $translation['Translation']['location']); ?>" class="tab-pane <?php echo ($key == 0) ? 'active' : ''; ?> clearfix">
						<div class="row">
							<div class="col-sm-6">
								<h5 class="note note-success">Référence: English</h5>
							</div>
							<div class="col-sm-6">
								<h5 class="note note-success">Traduire en: <?php echo $siteSetting['Sitesetting']['other_language']; ?></h5>
							</div>
						</div> <?php 
				} ?>

					<div class="form-group clearfix">
						<div class="col-sm-6">
                            <?php echo $this->Form->input('msgid',array('placeholder'=>"",'class'=>'form-control','readonly' => 'readonly','name'=>'data[Translation]['.$key.'][msgid]','value'=>$translation['Translation']['msgid'],'label'=>false)); ?>
						</div>
						<div class="col-sm-6">
							<?php echo $this->Form->input('msgstr',array('placeholder'=>"",'class'=>'form-control','name'=>'data[Translation]['.$key.'][msgstr]','value'=>$translation['Translation']['msgstr'],'label'=>false)); ?>
						</div>
                        <div class="col-sm-6">
							<?php echo $this->Form->input('languageid',array('placeholder'=>"",'type' =>'hidden','class'=>'form-control','name'=>'data[Translation]['.$key.'][languageid]','value'=>$translation['Translation']['languageid'],'label'=>false)); ?>
                         </div>   
                         <div class="col-sm-6">
							<?php echo $this->Form->input('location',array('placeholder'=>"",'type' =>'hidden','class'=>'form-control','name'=>'data[Translation]['.$key.'][location]','value'=>$translation['Translation']['location'],'label'=>false)); ?>                                
						</div>
					</div>

					 <?php //echo $translations[$nextValue]['Translation']['location'];
				if (!isset($translations[$nextValue]['Translation']['location']) ||
						$translations[$nextValue]['Translation']['location'] != $main) { ?>
					</div> <?php
				}
			endforeach; ?>
				
		</div>
		<div class="col-sm-12">
			<div class="pull-right">
				<button type="button" class="btn btn-danger"><?php echo __('Cancel')?></button>
	            <?php
				echo $this->Form->button(__('Submit'), array('class'=>'btn blue'));
				?>
			</div>
		</div>
	</div> <?php
echo $this->Form->end();