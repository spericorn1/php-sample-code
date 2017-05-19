@extends('layouts.storeapp')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>Categories</h2>
        <ol class="breadcrumb">
            <li>
                <a ui-sref="orders.new_orders">Home</a>
            </li>
            <li >
                Categories 
            </li>
			 <li class="active">
                <strong>Categories</strong>
            </li>
        </ol>
    </div>
    <div class="col-sm-8">
        
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight ecommerce" ng-app="apps" ng-controller="store">
    <div class="ibox-content m-b-sm border-bottom" >
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group" >
                    <label class="control-label" for="product_name">Select</label><br>
					<select  class="form-control alls" id="alls" ng-model="type" ng-change="selectetype(type)">
                       <option value="all" >ALL</option>
					   <option value="0">General</option>
					   <option value="1">Departments</option>
					</select>
				</div>
            </div>
            
            <div class="col-sm-4 dpts hide" >
                <div class="form-group">
                    <label class="control-label" for="status">Department</label>
                    <select  id="departments_id" name="departments_id" class="form-control" ng-model="department">
                       <option >Select option</option>
					</select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="quantity">Category</label>
                    <input type="text" id="quantity" name="quantity" value="" placeholder="Category" class="form-control" ng-model="category">
                </div>
            </div>

        </div>
        <div class="text-right">
            <button type="button" class="btn btn-w-m btn-primary" >
                Search
            </button>
        </div>
    </div>
<div class="row"  >
    <div class="col-lg-12">
	<div class="ibox">
            <div class="ibox-content">
			   <div class="row">
					<div class="inbox-table">
						
						<!-- list category  >> -->
						<div class="inbox-table-head" ng-init="listcategory()">
							<div class="">
								<div class="s_col_1">
									Name
								</div>
								<div class="s_col_1">
									Departments/General
								</div>
								<div class="s_col_1">
									Description
								</div>
								<div class="s_col_1">
									Action
								</div>
							</div>
						</div>
						
						<div class="inbox-table-row" ng-show="listcategory.length==0" style="border-bottom:1px solid #ddd">
							<div class="">
							<div class="s_col_1">
								No Categories
							</div></div>
						</div>
						
						<div class="inbox-table-row" dir-paginate="subCate in listcategory | filter:department |  filter:category |selectedTags:tags | itemsPerPage:7 ">
							<div class="" >
								<div class="s_col_1">
									@{{ subCate.categoryname }}
								</div>
								<div class="s_col_1">
									@{{ subCate.dptname }}
								</div>
								<div class="s_col_1">
									@{{ subCate.description }}
								</div>
								<div class="s_col_1">
								   @permission('edit-category') 					
									{{ Form::open(array('url' => 'editcategory','class' => 'pull-left')) }}<input type="hidden" name="id" value="@{{subCate.id}}"> <input type="submit" class="btn btn-primary btn-sm" value="Edit"></form>
									@endpermission
									@permission('delete-category') 
									<a style="margin-left: 3px;" class="btn btn-danger btn-sm" onClick="TakeId(this)"  data-toggle="modal" data-target="#DeleteModal" data-id="@{{subCate.id}}">Delete</a>
									@endpermission
								</div>
								<div class="inbox-table-row" ng-repeat="firstNestedSub in subCate.subCategory">
									<div class="">
										<div class="s_col_1">
											--@{{ firstNestedSub.categoryname }}
										</div>
										<div class="s_col_1">
											@{{ firstNestedSub.dptname }} 
										</div>
										<div class="s_col_1">
											@{{ firstNestedSub.description }}
										</div>
										<div class="s_col_1">
										  @permission('edit-category')  								
										   {{ Form::open(array('url' => 'editcategory','class' => 'pull-left')) }}<input type="hidden" name="id" value="@{{firstNestedSub.id}}"> <input type="submit" class="btn btn-primary btn-sm" value="Edit"></form>
										  @endpermission
										  @permission('delete-category') 
											<a style="margin-left: 3px;" class="btn btn-danger btn-sm" onClick="TakeId(this)"  data-toggle="modal" data-target="#DeleteModal" data-id="@{{ firstNestedSub.id }}">Delete</a>
                                          @endpermission
										</div>
										<div class="inbox-table-row" ng-repeat="secondNestedSub in firstNestedSub.subCategory">
											<div class="">
												<div class="s_col_1">
													----@{{ secondNestedSub.categoryname }}
												</div>
												<div class="s_col_1">
													@{{ secondNestedSub.dptname }}
												</div>
												<div class="s_col_1">
													@{{ secondNestedSub.description }}
												</div>
												<div class="s_col_1">
													@permission('edit-category')  								
													{{ Form::open(array('url' => 'editcategory','class' => 'pull-left')) }}<input type="hidden" name="id" value="@{{secondNestedSub.id}}"> <input type="submit" class="btn btn-primary btn-sm" value="Edit"></form>
												    @endpermission
													@permission('delete-category') 
													<a style="margin-left: 3px;" class="btn btn-danger btn-sm" onClick="TakeId(this)"  data-toggle="modal" data-target="#DeleteModal" data-id="@{{ secondNestedSub.id }}">Delete</a>
													 @endpermission
												</div>
												<div class="inbox-table-row" ng-repeat="thirdNestedSub in secondNestedSub.subCategory">
													<div class="">
														<div class="s_col_1">
															----@{{ thirdNestedSub.categoryname }}
														</div>
														<div class="s_col_1">
															@{{ thirdNestedSub.dptname }}
														</div>
														<div class="s_col_1">
															@{{ thirdNestedSub.description }}
														</div>
														<div class="s_col_1">
															@permission('edit-category')  								
															{{ Form::open(array('url' => 'editcategory','class' => 'pull-left')) }}<input type="hidden" name="id" value="@{{thirdNestedSub.id}}"> <input type="submit" class="btn btn-primary btn-sm" value="Edit"></form>
														    @endpermission
															@permission('delete-category') 
															<a style="margin-left: 3px;" class="btn btn-danger btn-sm" onClick="TakeId(this)"  data-toggle="modal" data-target="#DeleteModal" data-id="@{{ thirdNestedSub.id }}">Delete</a>
															 @endpermission
														</div>
													</div>
											  </div>
											</div>
								        </div>
							        </div>
						       </div>
							</div>
						</div>
						<dir-pagination-controls 
						boundary-links="true" 
						direction-links="true" >
						</dir-pagination-controls>	
					</div>
				</div>
			 </div>
        </div>
       
    </div>
</div>
	
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="H3">Delete this record?</h4>
		  </div>
		  <div class="modal-body">
		      Are you sure to delete this record?
		  </div>
		  <div class="deletesucess" style="width:50%;margin-left:10px;text-align:center"></div>
		  <div class="modal-footer">
				<input type="hidden" id="addCategory_id">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" onClick="DeleteCategory()">Delete</button>
		  </div>
		</div>
  </div>
</div>
@endsection
