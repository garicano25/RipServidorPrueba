@extends('template/maestra')
@section('contenido')

<style>
    :root {
        --img-size: 70%;
    }

    .norma-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 30px;
        margin-top: 40px;
        padding: 20px;
        justify-items: center;
    }

    .norma-card {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #009688;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: transform .3s ease, box-shadow .3s ease;
        overflow: hidden;
        position: relative;
    }

    .norma-card:hover {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.25);
    }

    .norma-card img {
        width: 88%;
        height: 86%;
        object-fit: contain;
        transition: transform .3s ease;
    }

    .norma-card:hover img {
        transform: scale(1.15);
    }

    .norma-title {
        text-align: center;
        margin-top: 10px;
        font-weight: bold;
        font-size: 16px;
        color: #333;
    }

    a.norma-link {
        text-decoration: none;
        color: inherit;
    }
</style>

<div class="container">
    <h2 class="text-center mt-3">Normas disponibles</h2>

    <div class="norma-grid">

        <a href="{{route('eppcatalogos.index')}}" class="norma-link">
            <div class="norma-card">
                <img src="/assets/images/nom-017.png" alt="NOM-035">
            </div>
            <div class="norma-title">NOM-017-STPS-2024</div>
        </a>

    </div>
</div>

@endsection