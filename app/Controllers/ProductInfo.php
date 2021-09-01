<?php 
	
namespace App\Controllers ;
use App\Models\ProductModel ;
use App\Models\CustomerModel ;
/**
 * 
*/
class ProductInfo extends BaseController
{       
	public function __construct()
	{
			$this->ProductModel = new ProductModel() ;
			$this->CustomerModel = new CustomerModel() ;        
	}

	public function index($page = 'ProductInfo')
	{   
        // SQL合併表單			
		$query = $this->ProductModel
				 ->select('product-info.id AS prod_id , product-info.*,customer-info.id AS cust_id, customer-info.companyName')
				 ->join('customer-info','customer-info.id = product-info.customerId')
				 ->orderBy('cust_id ,prod_id ')
				 ->find() ;
        
        //組合表單的順序
		if(isset($query[0]['companyName']))
		{			
			$text[0]['companyName']   = $query[0]['companyName']    ;
			$text[0]['cust_id']       = $query[0]['cust_id']      ;

			for($i=0 , $j = 0 ,$index=0 ; $i<count($query) ; $i++)
			{           
				if($text[$index]['cust_id'] != $query[$i]['cust_id'])
				{
					$index += 1 ;
					$text[$index]['companyName'] = $query[$i]['companyName'] ;
					$text[$index]['cust_id']     = $query[$i]['cust_id']     ;      
				}

				if($query[$i]['cust_id'] == $text[$index]['cust_id'])
				{   
					$text[$index]['query'][$j] = $query[$i]  ;
					$j++ ;                          
				}           
			}
				return view('Pages/BasicInfo/ProductInfo/index',[           
					'text' => $text 
				]) ;
		}else{
				return view('Pages/BasicInfo/ProductInfo/index',[
					'text' => $query
				]);
		}

	}		

	public function add()
    {
		return view('Pages/BasicInfo/ProductInfo/add',[
			'query' => $this->CustomerModel->select('id , companyName')
											->orderBy('id')
											->find() 
			]) ;
	}

	public function runAdd()
	{           
		$this->ProductModel->insert([
			'customerId'    => $this->request->getPost('customerId'),
			'productNum'    => $this->request->getPost('productNum'),
			'productSpec'   => $this->request->getPost('productSpec'),
			'unit'          => $this->request->getPost('unit'),
			'unitPrice'     => $this->request->getPost('unitPrice'),
			'sellingPrice'  => $this->request->getPost('sellingPrice'), 
			'PS'            => $this->request->getPost('PS')
		]);
		return redirect()->to('/ProductInfo') ;
	}

	public function edit()
	{
	    $query = $this->CustomerModel
					  ->select('id As cust_id , companyName')
					  ->orderBy('cust_id')
					  ->find() ;

		$product = $this->ProductModel
					    ->select('product-info.id AS prod_id , product-info.* , customer-info.id , customer-info.companyName')
					    ->join('customer-info','customer-info.id = product-info.customerId')
					    ->find($this->request->getGet('id')) ;      

		return view('Pages/BasicInfo/ProductInfo/edit',[
				'query' => $query ,
				'product' => $product
		]) ;
	}

	public function runEdit()
	{
		$this->ProductModel->update($this->request->getPost('id'),[
				'customerId'    => $this->request->getPost('customerId'),
				'productNum'    => $this->request->getPost('productNum'),
				'productSpec'   => $this->request->getPost('productSpec'),
				'unit'          => $this->request->getPost('unit'),
				'unitPrice'     => $this->request->getPost('unitPrice'),
				'sellingPrice'  => $this->request->getPost('sellingPrice'), 
				'PS'            => $this->request->getPost('PS')
		]) ;
			return redirect()->to('/ProductInfo') ;         
	}

	public function delete()
	{
		$this->ProductModel->where('id', $this->request->getPost('id'))->delete() ;
		return redirect()->to('/ProductInfo') ;
	}
}



 ?>