<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Image;
use App\User;
use App\Role;
use App\Product;
use App\Productinventory;
use App\Store;
use App\Category;
use App\Departments;
use DB;
use Session;
use Hash;
class SubCate
    {
        
        public function getCategoriesdpt($store_id,$id){
			$categories=\App\Category::join('departments', 'departments.id', '=', 'categories.departments_id')
               
                ->select('categories.*')
                ->where('categories.parent_id', 0)
				->where('categories.store_id', $store_id)
				->where('departments.id', $id)
                ->get();
            $categories=$this->addRelation($categories);

            return $categories;

        } public function getCategoriesdpts($store_id,$id,$category){
			$categories=\App\Category::join('departments', 'departments.id', '=', 'categories.departments_id')
                ->select('categories.*')
                ->where('categories.parent_id', 0)
				->where('categories.store_id', $store_id)
				->where('departments.id', $id)
				->where('categories.id', '!=', $category)
                ->get();
            $categories=$this->addRelation($categories);

            return $categories;

        } public function getCategories($id,$type){
			$categories=\App\Category::where('parent_id',0)->where("store_id", $id)->where("type", $type)->get();//united

            $categories=$this->addRelation($categories);

            return $categories;

        } public function getCategoriesedit($store_id,$id,$type){
			$categories=\App\Category::where('parent_id',0)->where("store_id", $store_id)->where('id', '!=', $id)->where("type", $type)->get();//united

            $categories=$this->addRelation($categories);

            return $categories;

        }public function showCategorieslist($store_id){
			$categories=\App\Category::join('departments','departments.id','=','categories.departments_id')
             ->select('categories.*','departments.name as dptname ')
             ->where('categories.parent_id',0)
			 ->where("categories.store_id", $store_id)
			 ->get();

            $categories=$this->addRelation($categories);

            return $categories;

        }public function getCategorieslist($store_id){
			

            $categories=\App\Category::where('parent_id',0)->where("store_id", $store_id)->get();

            $categories=$this->addRelation($categories);

            return $categories;

        }

        protected function selectChild($id)
        {
			$ids = Session::get('store_userid');
            $categories=\App\Category::where('parent_id',$id)->get(); 

            $categories=$this->addRelation($categories);

            return $categories;

        }

        protected function addRelation($categories){

            $categories->map(function ($item, $key) {
                
                $sub=$this->selectChild($item->id); 
                
                return $item=array_add($item,'subCategory',$sub);

            });

            return $categories;
        }
    }
class CategorysController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index() {
        $store_id = Session::get('store_userid');
        $dept = DB::table('departments')->where('store_id', '=', $store_id)->get();
		return view('storeuser.category', ['dept' => $dept];
    }

    public function categorylist() {
        
        return view('storeuser.categorylist');
    } 
	
	/*listing Category*/
	public function listingcategory() {
		
        $store_id = Session::get('store_userid');
        $subcate=new SubCate;
        try {

            $categories=$subcate->showCategorieslist($store_id);
              
        } catch (Exception $e) {
            
            //no parent category found
        }
         print_r(json_encode($categories));
        
    }
    /*Edit Category*/
    public function editcategory(Request $request) {
        $input = $request->all();
		$id=$input['id'];
        $store_userid = Session::get('store_userid');
		$subcate=new SubCate;
		try {

            $categories=$subcate->getCategoriesedit($store_userid,$id,1);
            $categoriesnon = $subcate->getCategoriesedit($store_userid,$id,0);
        } catch (Exception $e) {
            
            //no parent category found
        }
        $dept = DB::table('departments')->where('store_id', '=', $store_userid)->get();
        $data = Category::find($id);
        return view('storeuser.editcategory', ['dept' => $dept, 'data' => $data, 'categories' => $categories, 'categoriesnon' => $categoriesnon])->with('categories', $categories);
    }
    /*Delete Category*/
    public function deletecategory(Request $request) {
        $input = $request->all();
        $categories = Category::where("parent_id", $input['id'])->get();

        if (count($categories) > 0) {
            print_r(json_encode(array('status' => 'Failed', 'msg' => 'Parent Exist Please Delete The parent')));
        } else {
			$product =Product::where('category_id',$input['id'])->get();
			
			if(count($product)>0){
				foreach($product as $prdct){
				
					Productinventory::where('product_id',$prdct->id)->delete();
				}
				
			}
			$product =Product::where('category_id',$input['id'])->delete();
			
            Category::where('id', $input['id'])->delete();
            print_r(json_encode(array('status' => 'success', 'msg' => 'Deleted Succesfully')));
        }
    }
    /*Add Category*/
    public function addcategory(Request $request) {
        $input = $request->all();
		$id = Session::get('id');
		$result =Category::where('categoryname',$input['categoryname'])->where('departments_id',$input['departments_id'])->get();
		if(count($result)==0){
			/* image upload*/
		    if (Input::file('image')) {

				$image = Input::file('image');
				$filename = time() . '.' . $image->getClientOriginalExtension();
				$name = Input::file('image')->getClientOriginalName();
				$extension = $image->getClientOriginalExtension();
				// RENAME THE UPLOAD WITH RANDOM NUMBER 
				$fileName = rand(11111, 99999) . '.' . $extension;
				$destinationPath = public_path('upload/categories');
				$thumb_img = Image::make($image->getRealPath())->resize(200, 140);
                $thumb_img->save($destinationPath.'/'.$fileName,80);
				$input['image'] = 'upload/categories/' . $fileName;
				$pic=$input['image'];
			}
		
			$user = User::find($id);
			$input['store_id'] = $user->store_id;
            $create = Category::create($input);
            print_r(json_encode(array('status' => 'success', 'class' => 'alert alert-success','msg' => 'Category Created Succesfully','pic'=>$pic)));
		}else{
			print_r(json_encode(array('status' => 'Failed', 'class' => 'alert alert-danger','msg' => 'Category Exist')));
		}
       
    }
    /*Listing Store User and store details*/
    public function liststoreuser(Request $request) {
        $id = Session::get('id');
        $user = User::find($id);
        $users = Store::find($user->store_id);
        $list = DB::table('users')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*', 'roles.display_name', 'roles.id as role_id ')
                ->where('users.store_id', $user->store_id)
                ->get();
        print_r(json_encode(array('users' => $users, 'storeuser' => $list)));
    }
    /*Listing Store User */
    public function liststoresuser(Request $request) {
        $id = $request->id;
        $user = User::where("store_id", $id);
        print_r(json_encode($user));
    }
   /*Edit Store */
    public function editstoredata(Request $request) {
        $input = $request->all();
        $id = $input['id'];
        Store::where("id", $id)->update($input);
        print_r(json_encode(array('status' => 'success', 'msg' => 'Store Updated Succesfully')));
    }
    /*select Category based departmnts */
    public function selectcategory(Request $request) {
        $input = $request->all();
        $id = $input['id'];
        $categories = Category::where("departments_id", $id)->get();
        print_r(json_encode($categories));
    }
	
}
