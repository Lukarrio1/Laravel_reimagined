<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<div id="root"></div>
<script type="text/javascript" src="https://unpkg.com/babel-standalone@6/babel.js"></script>

<script type="text/babel">

    function App() {
        const [display_aid,setDisplayAid]= React.useState([])
        const [owned_display_aid,setOwnedDisplayAid]= React.useState([])
        const [owners,setOwners]= React.useState([])
        const [owned,setOwned]= React.useState([])
        const [references,setReferences]= React.useState([])


        const [form,setForm] = React.useState({})

        function createQueryString(params) {
        const queryString = Object.keys(params)
        .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
        .join('&');
        return queryString;
        }

        const getData =async(prop={})=>{
            const {data} = await axios.get('/references_ajax?'+createQueryString(prop))
            setDisplayAid(data.model_fields)
            setOwners(data.owners)
            setOwnedDisplayAid(data.owned_model_fields)
            setOwned(data.owned)
            setReferences(data.references)
        }

        const submitData=async()=>{
            const {data} = await axios.post('/reference',form)
            getData()
        }

        const deleteRef = async(id)=>{
            const {data} = await axios.delete('/reference/'+id)
            setReferences(references.filter(r=>r.id!=id))
        }

        React.useEffect(()=>{
        console.log(form,"this is the form")
        getData(form)
        },[form])

    return <div className="row">
           <div class="col-sm-8 offset-sm-2 mt-5">
            <div className="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
            <div className="card-header text-center bg-white h3 fw-bold">
            Reference Management
            </div>
            <div class="card-body">
                <form>
                 <div class="mb-3">
                     <label for="owner_model" class="form-label">Owner Model</label>
                     <select id="owner_model" class="form-select trigger" name="owner_model"
                     onChange={(e)=>setForm({...form,owner_model:e.target.value})}>
                         <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <option value="<?php echo e($model); ?>" data-node-type="<?php echo e($model); ?>" <?php echo e(optional(optional($reference)->owner_model) ==$model || request('owner_model')==$model?"selected":''); ?>><?php echo e($model); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </select>
                     <?php $__errorArgs = ['owner_model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                     <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>
                 <div class="mb-3">
                     <label for="owner_model_display_aid" class="form-label">Owner Model Display Aid</label>
                     <select id="owner_model_display_aid" class="form-select trigger" name="owner_model_display_aid"
                      onChange={(e)=>setForm({...form,owner_model_display_aid:e.target.value})}
                     >
                        {display_aid.length>0&&display_aid.map(function(aid){
                            const selected = form.owner_model_display_aid==aid?"selected":''
                            return <option value={aid} selected={selected}>{aid}</option>
                        })}
                     </select>
                     <?php $__errorArgs = ['owner_model_display_aid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                     <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>
                    <div class="mb-3">
                        <label for="owner_item" class="form-label">Owner Item</label>
                        <select id="owner_item" class="form-select trigger" name="owner_item" onChange={(e)=>setForm({...form,owner_item:e.target.value})}>
                            {owners.length>0&&owners.map(function(owner){
                            const selected = form.owner_item==owner.id?"selected":''
                            return <option value={owner.id} selected={selected}>{owner[form.owner_model_display_aid]}</option>

                            })}
                        </select>
                        <?php $__errorArgs = ['owner_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                      <div class="mb-3">
                          <label for="owned_model" class="form-label">Owned Model</label>
                          <select id="owned_model" class="form-select trigger" name="owned_model" onChange={(e)=>setForm({...form,owned_model:e.target.value})}>
                              <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($model); ?>" data-node-type="<?php echo e($model); ?>" <?php echo e(optional(optional($reference)->owner_model) ==$model || request('owned_model')==$model?"selected":''); ?>>
                                  <?php echo e($model); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>
                          <?php $__errorArgs = ['owner_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                          <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                      </div>

                    <div class="mb-3">
                        <label for="owned_model_display_aid" class="form-label">Owned Model Display Aid</label>
                        <select id="owned_model_display_aid" class="form-select trigger" name="owned_model_display_aid"
                        onChange={(e)=>setForm({...form,owned_model_display_aid:e.target.value})}
                        >
                        {owned_display_aid.length>0&&owned_display_aid.map(function(item){
                                const selected = form.owned_model_display_aid==item?"selected":''
                            return <option value={item} selected={selected}>
                                {item}</option>

                        })}
                        </select>
                        <?php $__errorArgs = ['owned_model_display_aid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label for="owned_item" class="form-label">Owned Item</label>
                        <select id="owned_item" class="form-select" name="owned_item"
                          onChange={(e)=>setForm({...form,owned_item:e.target.value})}
                        >
                         {owned.length>0&&owned.map(function(item){
                         const selected = form.owned_item==item.id?"selected":''
                         return <option value={item.id} selected={selected}>
                             {item[form.owned_model_display_aid]}</option>
                         })}
                        </select>
                        <?php $__errorArgs = ['owned_model_display_aid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" class="form-select" name="type"
                          onChange={(e)=>setForm({...form,type:e.target.value})}
                        >
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(request('type')==$type?"selected":''); ?>><?php echo e($type); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="text-center">
                        <button className="btn btn-primary btn-sm" onClick={(e)=>{
                            e.preventDefault()
                            submitData()
                        }}>submit</button>
                    </div>

                </form>
            </div>
            </div>
            </div>
            <div class="col-sm-10 offset-sm-1 mt-3">
             <div class="card  shadow-lg p-3 mb-5 bg-body-tertiary rounded">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center h4 fw-bold ">Owner Model</th>
                            <th scope="col" class="text-center h4 fw-bold ">Owned Model</th>
                            <th scope="col" class="text-center h4 fw-bold ">Description</th>
                            <th scope="col" class="text-center h4 fw-bold ">Type</th>
                             <th scope="col" class="text-center h4 fw-bold ">References greater than 1</th>
                            <th scope="col" class="text-center h4 fw-bold ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                {references.length>0&&references.map(function(ref){
                    return <tr>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold">{ref.owner_model}</div>
                        </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold">{ref.owned_model}</div>
                        </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold">{ref.description}</div>
                        </td>
                         <td>
                             <div class="text-bg-light text-center p-3 fw-semibold">{ref.type}</div>
                         </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold">{ref.has_many?"TRUE":"FALSE"}</div>
                        </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold">
                            <button onClick={()=>deleteRef(ref.id)} class="btn btn-danger btn-sm h4" title="delete node">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                               </button>
                            </div>
                        </td>
                    </tr>
                })}
                    </tbody>
                </table>
            </div>

             </div>
            </div>
            </div>;


  }

  // Render the component to the DOM
  ReactDOM.render(
    <App />,
    document.getElementById("root")
  );
</script>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Reference/Create.blade.php ENDPATH**/ ?>