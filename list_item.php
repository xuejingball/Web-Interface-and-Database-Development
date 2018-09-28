<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$username = $_SESSION['username'];
        $item_name = mysqli_real_escape_string($db, $_POST['item_name']);
        $description = mysqli_real_escape_string($db, $_POST['description']);
        $get_it_now_price = mysqli_real_escape_string($db, $_POST['get_it_now_price']);
        $minimum_sale_price = mysqli_real_escape_string($db, $_POST['minimum_sale_price']);
        $returnable = mysqli_real_escape_string($db, isset($_POST['returnable']) ? 1 : 0);
        $starting_bid = mysqli_real_escape_string($db, $_POST['starting_bid']);
				$auction_end_time = date('Y-m-d H:i:s', strtotime($_POST['time_length']));
				$category_name = mysqli_real_escape_string($db, $_POST['category_name']);
        $condition_name = mysqli_real_escape_string($db, $_POST['condition_name']);
        if (empty($item_name)) {
                array_push($error_msg,  "Please enter a item name.");
        }

        if (empty($description)) {
            array_push($error_msg,  "Please enter a description.");
        }

        if (empty($starting_bid)) {
                array_push($error_msg,  "Please enter a start auction bidding price.");
        } else if(is_numeric($starting_bid)) {
					$starting_bid = number_format($starting_bid, 2, '.', '');
				} else {
					array_push($error_msg,  "Please enter a valid start auction bidding price.");
				}


        if (empty($minimum_sale_price)) {
                array_push($error_msg,  "Please enter a minimum sale price.");
        } else if(is_numeric($minimum_sale_price)) {
					$minimum_sale_price = number_format($minimum_sale_price, 2, '.', '');
				} else {
					$minimum_sale_price = '';
					array_push($error_msg,  "Please enter a valid minimum sale price.");
				}
				if($minimum_sale_price < $starting_bid) {
					array_push($error_msg,  "Mininum sale price should be equal or higher than starting bid prid.");
				}
				if(!empty($get_it_now_price)) {
					if(is_numeric($get_it_now_price)) {
					 $get_it_now_price = number_format($get_it_now_price, 2, '.', '');
					 if($get_it_now_price <= $minimum_sale_price) {
						array_push($error_msg,  "Get it now price should be higher than minimum sale price.");
					}
				 } else {
					 array_push($error_msg,  "Please enter a valid get it now price.");
				 }
				}


         if ( !empty($item_name) && !empty($description) && !empty($starting_bid) && !empty($minimum_sale_price) && empty($error_msg) )   {
					 if (empty($get_it_now_price)) {
						 $query = "INSERT INTO Item (itemID, username, item_name, description, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) " .
                      "values (NULL, '$username', '$item_name', '$description', '$minimum_sale_price', '$returnable', '$starting_bid', '$auction_end_time', '$category_name', '$condition_name')";
					 } else {


						 $query = "INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) " .
                      "values (NULL, '$username', '$item_name', '$description', '$get_it_now_price', '$minimum_sale_price', '$returnable', '$starting_bid', '$auction_end_time', '$category_name', '$condition_name')";

					 }


            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
						// echo $query;
						if($result){
							$_POST = "";
							echo '<script type="text/javascript"> alert("Item added") </script>';

						}else{
							echo '<script type="text/javascript"> alert("Add Item Failed!") </script>';
						}
					}

				}


?>

        <?php include("lib/menu.php"); ?>
				<link rel="stylesheet" href="css/style.css">
		<div class="container">

			<div class="center_content">
				<div class="col-lg-10 col-lg-offset-1">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>
					<div class="features">

                        <div class="profile_section">
							<h3> New Item for Auction </h3>

							<form name="itemform" action="list_item.php" method="post">
								<div class = "messages"></div>
									<div class = "controls">
										<!-- row 1 -->
										<div class = "row">
											<div class = "col-md-12">
												<div class = "form-group">
													<label for = "itemname_form"> Item Name *</label>
													<input id = "itemname_form" type = "text" name = "item_name" value = "<?php echo isset($_POST['item_name'])?$_POST['item_name']:''?>" class = "form-control" placeholder = "please enter your item name *" required = "required" data-error = "Item name is required.">
								 					<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
										<!-- row 2 -->
										<div class = "row">
											<div class="col-md-12">
                				<div class="form-group">
                    			<label for="pricename_form">Get It Now price $ </label>
                    			<input id="pricename_form" type="text" name="get_it_now_price" value = "<?php echo isset($_POST['get_it_now_price'])?$_POST['get_it_now_price']:''?>" class="form-control" placeholder="Please enter your get it now price">
													<div> (optional)</div>
                    			<div class="help-block with-errors"></div>
                				</div>
            					</div>
										</div>
										<!-- row 3 -->
										<div class = "row">
											<div class = "col-md-12">
												<div class = "form-group">
													<label for = "startbidprice_form"> Start bidding at $ *</label>
													<input id = "startbidprice_form" type = "text" name = "starting_bid" value = "<?php echo isset($_POST['starting_bid'])?$_POST['starting_bid']:''?>" class = "form-control" placeholder = "please enter your start auction bidding price *" required = "required" data-error = "Start bidding price is required.">
								 					<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
										<div class = "row">
									 		<div class="col-md-12">
                				<div class="form-group">
                    			<label for="minimumsaleprice_form">Minimum sale price $ * </label>
                    			<input id="minimumsaleprice_form" type="text" name="minimum_sale_price" value ="<?php echo isset($_POST['minimum_sale_price'])?$_POST['minimum_sale_price']:''?>" class="form-control" required = "required" placeholder="Please enter your minimum sale price *">
                    			<div class="help-block with-errors"></div>
                				</div>
            					</div>
										</div>
										<!-- row 3 -->
										<div class = "row">
											<div class = "col-md-6">
												<div class = "form-group">
													<label for = "category_form"> Category * </label>
													<select name = "category_name" id = "category_name" value = "<?php echo isset($_POST['category_name'])?$_POST['category_name']:''?>">;
													<?php
															$query = "SELECT category_name FROM Category";
															$result = mysqli_query($db, $query);
															 include('lib/show_queries.php');

															 if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
																array_push($error_msg,  "Query ERROR: Failed to get category name... <br>" . __FILE__ ." line:". __LINE__ );
															}
															if(isset($_POST["category_name"])) {
																print '<option value ="' . $_POST["category_name"] . '"selected >' . $_POST["category_name"] . '</option>';
															}

															 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
																 print $row['category_name'];
																 if ($row['category_name'] != $_POST["category_name"]) {
																 print '<option value ="' . $row['category_name'] .'">' . $row['category_name'] . '</option>';
															 }
														 }
														?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
											</div>
											<div class = "col-md-6">
												<div class = "form-group">
													<label for = "auctionend_form"> Auction ends in *</label>
													<select name = "time_length" id = "time_length">
													<?php
													$time_gap = array("1 day", "3 days", "5 days", "7 days");
													$arrlength = count($time_gap);
													if(isset($_POST["time_length"])) {
														print '<option value ="' . $_POST["time_length"] . '"selected >' . $_POST["time_length"] . '</option>';
													}

													 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
														 print $row['condition_name'];

													}
													for($x = 0; $x < $arrlength; $x++) {
														if ($time_gap[$x] != $_POST["time_length"]) {
														print '<option value ="' . $time_gap[$x] .'">' . $time_gap[$x] . '</option>';
														}
													}?>
												</select>

													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
										<!-- row 4 -->
										<div class = "row">
											<div class = "col-md-6">
												<div class = "form-group">
													<label for = "condition_name"> Condition * </label>
													<select name = "condition_name" id = "condition_name" value = "<?php echo isset($_POST['condition_name'])?$_POST['condition_name']:''?>">;
											<?php
															$query = "SELECT * FROM ConditionState";
															$result = mysqli_query($db, $query);
															 include('lib/show_queries.php');

			                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
			                          array_push($error_msg,  "Query ERROR: Failed to get condition name... <br>" . __FILE__ ." line:". __LINE__ );
			                        }
															if(isset($_POST["condition_name"])) {
																print '<option value ="' . $_POST["condition_name"] . '"selected >' . $_POST["condition_name"] . '</option>';
															}

															 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
																 print $row['condition_name'];
																 if ($row['condition_name'] != $_POST["condition_name"]) {
																 print '<option value ="' . $row['condition_name'] .'">' . $row['condition_name'] . '</option>';
															 }
															}
														?>
													</select>

								 					<div class="help-block with-errors"></div>
												</div>
											</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="returnable_form"> Returns Accepted? </label>
														<input type = "checkbox" name = "returnable" value = "<?php echo isset($_POST['returnable'])?$_POST['returnable']:''?>"/>
														<div class="help-block with-errors"></div>
													</div>
												</div>
											</div>

										<!-- row 6 -->
										<div class = "row">
											<div class = "col-md-12">
												<div class = "form-group">
													<label for = "description_form"> Item Description *</label>
													<textarea id = "description_form" name = "description" class = "form-control" placeholder = "please enter your item description *" rows = "4" required = "required" data-error = "Item description is required."><?php echo isset($_POST['description'])?$_POST['description']:''?></textarea>
								 					<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
									</div>



									<div class = "row">
									<div class="col-md-6">
											<input type="button" class = "button" value="Cancel" onClick = "window.location= 'view_profile.php';"/>
									</div>
									<div class="col-md-6">
									    <input type="submit" name = "list_btn" class="button" value="Save"/>
									</div>

								</div>
								<!-- <a href="javascript:profileform.submit();" name = 'btn_submit' class="btn btn-primary">Save</a> -->
								<!-- <a href="view_profile.php"><input type="button" id="back_button" value="Cancel"/></a> -->
								<!-- <input name="list_btn" type="submit" class="button" value="Save"/> -->
							</form>
						</div>




					 </div>

				</div>
				<?php include("lib/error.php"); ?>

				<div class="clear"></div>
			</div>

               <?php include("lib/footer.php"); ?>

		</div>
	</body>
</html>
