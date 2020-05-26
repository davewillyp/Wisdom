@extends ('staff.layout')
@section ('body')
<div class='row justify-content-between'>
    <div class='col-auto'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Create Item Category</span>
        </div>     
        <div class='container shadow-sm' style='background:whitesmoke;padding:5px 20px 10px 20px;color:#003366;font-size:16px;border-left: 3px solid #003366'>
            <form method='post' action='/admin/bookings/category' id='createCategory'>
                @csrf
                <div class="form-row">
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="inlineFormCustomSelect">Category Name</label>
                        <input name='name' type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name') }}">                           
                    </div>
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="customControlAutosizing">Icon</label>      
                        <input name='icon' type="text" class="form-control form-control-sm @error('icon') is-invalid @enderror" value="{{ old('icon') }}">        
                    </div>
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="customControlAutosizing">Colour</label>      
                        <input name='colour' type="text" class="form-control form-control-sm @error('colour') is-invalid @enderror" value="{{ old('colour') }}">        
                    </div>
                    <div class="col-auto mt-auto">                        
                        <i class="fal fa-plus-circle addplus" style='font-size:20px;margin-bottom:12px' onclick='submitCategory()'></i>
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>
<br>
<div class='row justify-content-between'>
    <div class='col'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Categories</span>
        </div>
            <form id='deleteCategory' method='post' action='/admin/bookings/category'>
            @csrf
            @method('delete')
            <input type='hidden' id='categoryid' name='id'>            
            </form>
            
            @foreach ($cats as $cat)
            <div class='row'>
                <div class='col-5' >   
                    <div class='container my-auto shadow-sm' style='background:whitesmoke;color:#003366;
                    border-left: 3px solid #003366;padding-top:5px;padding-bottom:5px;border-bottom:1px solid gainsboro;'>
                        <div class='row'>
                            <div class='col-10'>
                                <i class='{{ $cat->icon }} fa-fw' style='font-size:16px;color:{{ $cat->colour }}'></i>
                                <span class='pl-3' style='font-size:16px'>{{ $cat->name}}</span>                                
                            </div>
                            <div class='col my-auto'>
                                <i class="fal fa-trash-alt deletebin" style='font-size:18px' onclick='deleteCategory({{ $cat->id }})'></i>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            @endforeach   
        <br><br>
    </div>     
</div>
<br>
<div class='row justify-content-between'>
    <div class='col-auto'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Create Item</span>
        </div>     
        <div class='container shadow-sm' style='background:whitesmoke;padding:5px 20px 10px 20px;color:#003366;font-size:16px;border-left: 3px solid #003366'>
            <form method='post' action='/admin/bookings/item' id='createItem'>
                @csrf
                <div class="form-row">
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="inlineFormCustomSelect">Item Name</label>                       
                        <input name='name' type="text" class="form-control-sm form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">                           
                    </div>
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="customControlAutosizing">Category</label>      
                        <select name='category' class='form-control form-control-sm'>
                            @foreach($cats as $cat)
                                <option value='{{ $cat->id }}'>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>                    
                    <div class="col-auto mt-auto">                        
                        <i class="fal fa-plus-circle addplus" style='font-size:20px;margin-bottom:12px' onclick='submitItem()'></i>
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>
<br>
<div class='row justify-content-between'>
    <div class='col'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Items</span>
        </div>
            <form id='deleteItem' method='post' action='/admin/bookings/item'>
            @csrf
            @method('delete')
            <input type='hidden' id='itemid' name='id'>            
            </form>
            
            @foreach ($items as $item)
            <div class='row'>
                <div class='col-5' >   
                    <div class='container my-auto shadow-sm' style='background:whitesmoke;color:#003366;
                    border-left: 3px solid #003366;padding-top:5px;padding-bottom:5px;border-bottom:1px solid gainsboro;'>
                        <div class='row'>
                            <div class='col-10'>
                                <i class='{{ $item->icon }} fa-fw' style='font-size:16px;color:{{ $item->colour }}'></i>
                                <span class='pl-3' style='font-size:16px'>{{ $item->name}}</span>                                
                            </div>
                            <div class='col my-auto'>
                                <i class="fal fa-trash-alt deletebin" style='font-size:18px' onclick='deleteItem({{ $item->id }})'></i>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            @endforeach   
        <br><br>
    </div>     
</div>

<script>
    function submitCategory(){
        $('#createCategory').submit();
    }
    function deleteCategory(id){
        $('#categoryid').val(id);
        $('#deleteCategory').submit();
    }

    function submitItem(){
        $('#createItem').submit();
    }
    function deleteItem(id){
        $('#itemid').val(id);
        $('#deleteItem').submit();
    }
</script>
@endsection