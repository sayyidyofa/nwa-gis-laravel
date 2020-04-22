@extends('layouts.dashboard')

@section('content')
    <h3>Import GIS Dataset</h3>
    <p>Duplicate data will be skipped and not added to database</p>
    <form action="" method="post" class="ui centered form">
        <div class="field">
            <div class="ui massive icon input">
                <input type="file" accept=".xlsx, .xls, .csv" name="file" required>
                <i class="upload icon"></i>
            </div>
        </div>
        <button class="ui primary button" type="submit">Import</button>
    </form>
@endsection

@section('inline_js')
    <script>
        let callback = (value) => {
            console.log(value);
            Swal.fire("OK","GIS Dataset successfully imported to database!", "success");
        };
        function alertError(e) {
            Swal.fire(
                "Whoops!",
                `${e}`,
                "error"
            );
        }
        $("form").on('submit',(function(e) {
            e.preventDefault();
            $.fn.toast.settings.silent = true;
            let myToast = $('body').toast({
                class: 'info',
                title: 'Import',
                displayTime: 0,
                message: 'Uploading data.....'
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('gis.import') }}',
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                error: (jqXHR) => {
                    alertError(`${jqXHR.responseText.hasOwnProperty('message')?jqXHR.responseText.message:'Error'}! ${jqXHR.responseText.hasOwnProperty('errors')?jqXHR.responseText.errors: JSON.parse(jqXHR.responseText).message}`);
                    //console.log(jqXHR.responseText);
                    myToast.toast('close',{silent: true})},
                success: (data) => {

                    myToast.toast('close');
                    $('body').toast({
                        class: 'info',
                        title: 'Import',
                        message: 'Data upload complete!',
                        showProgress: 'bottom'
                    });
                    if (typeof callback === 'function')
                        callback(data);
                }
            });
            $('form').trigger('reset');
        }));
    </script>
@endsection
