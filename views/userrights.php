<form action="/user/saveRights/<?php echo $user->id ?>" method="post">
	<div class="row" style="border-top:1px solid grey;">
		<div class="nav nav-pills flex-column col-sm-2" role="tablist" id="user_rights_tabs">
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#warehouse" role="tab">Склады</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#sell_point" role="tab">Точки продаж</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#supplier" role="tab">Поставщики</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#buyer" role="tab">Покупатели</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#product" role="tab">Товары и группы</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#sell" role="tab">Продажи</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#company_info" role="tab">Информация о компании</a>
				</li>
		</div>
		<div class="tab-content col-sm-10" id="user_rights_pans">
				<!-- склады -->
				<div class="tab-pane" id="warehouse" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления складами</p>
							<div class="row">
								<?php foreach($user_rights['warehouse'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[warehouse]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<p>Общие настройки управления товарным запасом</p>
							<div class="row">
								<?php foreach($user_rights['product_managment'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[product_managment]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<?php foreach($warehouses as $warehouse): ?>
								<div class="row">
									<div class="col-xs-4"><?php echo $warehouse->name ?></div>
									<?php foreach($user_rights['warehouse'][$warehouse->id] as $user_right_type_name =>$user_right_type_value): ?>
										<div class="col-xs-4">
											<input type="checkbox" name="<?php echo 'user_rights'.'[warehouse]'.'['.$warehouse->id.']'.'['.$user_right_type_name.']' ?>" <?php echo $user_right_type_value ? "checked" : "" ?> />
											<label><?php echo $user_right_type_name ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!-- end склады -->
				<!-- точки продаж-->
				<div class="tab-pane" id="sell_point" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления точками продаж</p>
							<div class="row">
								<?php foreach($user_rights['sell_point'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[sell_point]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<?php foreach($sell_points as $sell_point): ?>
								<div class="row">
									<div class="col-xs-4"><?php echo $sell_point->name ?></div>
									<?php foreach($user_rights['sell_point'][$sell_point->id] as $user_right_type_name =>$user_right_type_value): ?>
										<div class="col-xs-4">
											<input type="checkbox" name="<?php echo 'user_rights'.'[sell_point]'.'['.$sell_point->id.']'.'['.$user_right_type_name.']' ?>" <?php echo $user_right_type_value ? "checked" : "" ?> />
											<label><?php echo $user_right_type_name ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!--end точки продаж-->
				<!-- Поставщики -->
				<div class="tab-pane" id="supplier" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления поставщиками</p>
							<div class="row">
								<?php foreach($user_rights['supplier'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[supplier]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<?php foreach($suppliers as $supplier): ?>
								<div class="row">
									<div class="col-xs-4"><?php echo $supplier->name ?></div>
									<?php foreach($user_rights['supplier'][$supplier->id] as $user_right_type_name =>$user_right_type_value): ?>
										<div class="col-xs-4">
											<input type="checkbox" name="<?php echo 'user_rights'.'[supplier]'.'['.$supplier->id.']'.'['.$user_right_type_name.']' ?>" <?php echo $user_right_type_value ? "checked" : "" ?> />
											<label><?php echo $user_right_type_name ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!--end Поставщики-->
				<!-- Покупатели -->
				<div class="tab-pane" id="buyer" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления покупателями</p>
							<div class="row">
								<?php foreach($user_rights['buyer'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[buyer]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<?php foreach($buyers as $buyer): ?>
								<div class="row">
									<div class="col-xs-4"><?php echo $buyer->name ?></div>
									<?php foreach($user_rights['buyer'][$buyer->id] as $user_right_type_name =>$user_right_type_value): ?>
										<div class="col-xs-4">
											<input type="checkbox" name="<?php echo 'user_rights'.'[buyer]'.'['.$buyer->id.']'.'['.$user_right_type_name.']' ?>" <?php echo $user_right_type_value ? "checked" : "" ?> />
											<label><?php echo $user_right_type_name ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!--end Покупатели-->
				<!--Товары, товарные группы -->
				<div class="tab-pane" id="product" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления Товарами</p>
							<div class="row">
								<?php foreach($user_rights['product'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[product]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления Товарными группами</p>
							<div class="row">
								<?php foreach($user_rights['product_group'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[product_group]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<p>Настройки товарных групп</p>
							<?php foreach($product_groups as $product_group): ?>
								<div class="row">
									<div class="col-xs-4"><?php echo $product_group->name ?></div>
									<?php foreach($user_rights['product_group'][$product_group->id] as $user_right_type_name =>$user_right_type_value): ?>
										<div class="col-xs-4">
											<input type="checkbox" name="<?php echo 'user_rights'.'[product_group]'.'['.$product_group->id.']'.'['.$user_right_type_name.']' ?>" <?php echo $user_right_type_value ? "checked" : "" ?> />
											<label><?php echo $user_right_type_name ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!--end товары и группы-->
				<!-- Продажи, цены и вознаграждения -->
				<div class="tab-pane" id="sell" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления продажами</p>
							<div class="row">
								<?php foreach($user_rights['sell'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[sell]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<p>Общие настройки управления ценами</p>
							<div class="row">
								<div class="col-xs-4">
									<p>Розница</p>
								</div>
								<?php foreach($user_rights['purchase_price'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-4">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[purchase_price]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="row">
								<div class="col-xs-4">
									<p>Мелкий опт</p>
								</div>
								<?php foreach($user_rights['small_opt_price'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-4">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[small_opt_price]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="row">
								<div class="col-xs-4">
									<p>Средний опт</p>
								</div>
								<?php foreach($user_rights['medium_opt_price'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-4">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[medium_opt_price]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="row">
								<div class="col-xs-4">
									<p>Крупный опт</p>
								</div>
								<?php foreach($user_rights['large_opt_price'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-4">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[large_opt_price]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<p>Настройки управления вознаграждениями:</p>
							<?php foreach($product_groups as $product_group): ?>
								<div class="row">
									<div class="col-xs-4"><?php echo $product_group->name ?></div>
									<?php foreach($user_rewards[$product_group->id] as $user_reward_name => $user_reward_v): ?>
											<div class="col-xs-2">
												<input type="text" name="<?php echo 'user_rewards'.'['.$product_group->id.']'.'['.$user_reward_name.']' ?>" value="<?php echo $user_reward_v ?>"/>
											</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!--end Продажи, цены и вознаграждения-->
				<!-- Продажи, цены и вознаграждения -->
				<div class="tab-pane" id="company_info" role="tabpanel">
					<div class="row">
						<div class="col-xs-12">
							<p>Управление правами редактирования информации о компании</p>
							<div class="row">
								<?php foreach($user_rights['company_info'][0] as $user_right_types_name => $user_right_types_value): ?>
									<div class="col-xs-6">
										<div class="input-group">
											<input type="checkbox" name="<?php echo 'user_rights[company_info]'.'[0]'.'['.$user_right_types_name.']' ?>" <?php echo $user_right_types_value ? "checked" : "" ?> />
											<label><?php echo $user_right_types_name ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<div class="row">
		<input class="btn" type="submit" value="Сохранить"/></div>
	</div>
</form>

<script>
$(document).ready(function(){
	$("#user_rights_tabs a.nav-link:first").addClass('active');
	$("#user_rights_pans div.tab-pane:first").addClass('active');
	$("#user_rights_tabs a.nav-link").click(function(e){
		e.preventDefault();
		$(this).tab('show');
	})
});
</script>