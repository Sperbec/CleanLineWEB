@extends('adminlte::page')

@section('title', 'Países')

@section('content_header')
    <div class="row">
        <h1>Países</h1>
        <a id="btnCrear" data-toggle="modal" data-target="#mdlCrearPais" class="btn btn-primary btn-sm ml-auto">
            <i class="fas fa-plus"></i> Crear país</a>
    </div>

@stop

@section('content')

@error ('codigo_pais')
<small><span style="color: red">{{$message}}</span></small>
<br>
@enderror
@error ('nombre_pais')
<small><span style="color: red">{{$message}}</span></small>
@enderror

    <!-- Modal de crear país-->
    <div id="mdlCrearPais" class="modal fade" role="dialog">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Crear país</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    {!! Form::open(['route' => 'pais.store']) !!}

                    <div class="row">
                        <div class="col-md-6">
                            <label for="codigo_pais">Codigo país:</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="far fa-keyboard"></i>
                                </div>
                                {!! Form::text('codigo_pais', null, ['id' => 'codigopais', 'class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="nombre_pais">Nombre país:</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-keyboard"></i>
                                </div>
                                {!! Form::text('nombre_pais', null, ['id' => 'nombrepais', 'class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button id="agregarItem" type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>




    <table class="table table-hover" id="tblpais">
        <thead>
            <tr>
                <td class="negrita">ID</td>
                <td class="negrita">Código</td>
                <td class="negrita">Nombre</td>
                <td class="negrita">Acciones</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($paises as $pais)
                <tr>
                    <td>{{ $pais->id_pais }}</td>
                    <td>{{ $pais->codigo }}</td>
                    <td>{{ $pais->nombre }}</td>
                    <td>
                        <div class="row">
                            <a id="btnEditar" data-toggle="modal" data-target="#mdlEditarPais{{ $pais->id_pais }}"
                                class="btn btn-primary opts" data-toggle="tooltip" data-bs-placement="top"
                                title="Editar país">
                                <i class="fas fa-edit"></i></a>

                            <form class="formEliminar" action="{{ route('pais.destroy', $pais->id_pais) }}"
                                method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                        </div>
                        </form>


                    </td>
                    @include('pais.editmodal')
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
<style>
    .negrita {
        font-weight: bold;
    }
</style>
@stop

@section('js')
    <script>
        //Modal de crear país
        $("#btnCrear").on("click", function() {
            document.getElementById('codigopais').value = '';
            document.getElementById('nombrepais').value = '';
            $("#mdlCrearPais").modal("show");
        });

        //Modal de editar país
        $("#btnEditar").on("click", function() {
            document.getElementById('codigopais').value = '';
            document.getElementById('nombrepais').value = '';
            $("#mdlEditarPais").modal("show");
        });


        $(document).ready(function() {
            $('#tblpais').DataTable({
                "language": idioma_espanol
            });
        });


        var idioma_espanol = {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }

        }

        @if (session('eliminado') == 'ok' || session('editado') == 'ok' || session('guardado') == 'ok')
            Swal.fire({
            position: 'top-end',
            icon: 'success',

            @if (session('eliminado') == 'ok')
                title: 'Registro eliminado con éxito',
            @endif

            @if (session('editado') == 'ok')
                title: 'Registro editado con éxito',
            @endif

            @if (session('guardado') == 'ok')
                title: 'Registro guardado con éxito',
            @endif

            showConfirmButton: false,
            timer: 1500
            })
        @endif


        $('.formEliminar').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Seguro de eliminar este registro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })

        });

        @if (session('error') == 'ok')
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No es posible eliminar el país porque está relacionado a un departamento.'
            })
        @endif
    </script>
@stop
