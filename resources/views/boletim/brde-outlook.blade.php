<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Security-Policy" content="script-src 'none'; connect-src 'none'; object-src 'none'; form-action 'none';"> 
    <meta charset="UTF-8"> 
    <meta content="width=device-width, initial-scale=1" name="viewport"> 
    <meta name="x-apple-disable-message-reformatting"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta content="telephone=no" name="format-detection"> 
    <title>Boletim Digital</title> 
    <style>

    </style> 
</head> 
<body style="background: white; font-family: Tahoma, Arial,sans-serif; font-size: 12px; padding-top: 20px; padding-bottom: 20px;">
    <div style="width: 800px; margin: 0 auto; background: white; padding: 10px 20px; margin-top: 30px;">
    <table width="800px;" style="width: 800px; background: white;">
        <tr>
            <td>
            <table width="800px;" style="width: 800px;">
                <tbody>
                    <tr>
                        @if($boletim->cliente->fl_banner == "y")
                            <td>
                                <img src="{{ asset('img/banner/'.$boletim->cliente->logo ) }}">
                            </td>
                        @else
                            <td style="width: 50%;">
                                <img style="width: 40%;" src="{{ asset('img/logos/'.$boletim->cliente->logo ) }}">
                            </td>
                            <td style="width: 50%; text-align: right;">
                                @if($boletim->cliente->exibir_logo == 'y')
                                    <img style="width: 25%; position: absolute; bottom: 40px; right: 45px;" src="{{ asset('img/logo_studio_clipagem.jpg') }}">
                                @endif
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            <div style="text-align: right;">
                @if(count($dados) > 1)
                    <span>Foram encontradas {{ count($dados) }} notícias</span>
                @else
                    <span>Foi encontrada {{ count($dados) }} notícia</span>
                @endif
            </div>  
            <div style="text-align: right; margin-top: 5px;">
                <span><a href="{{ url('boletim/'.$boletim->id.'/visualizar') }}">Clique aqui</a> para ver o boletim no navegador</span>
            </div>      

            @php
                $uf = "";
                $area = "";
                $tipo = "";
                $tipo_formatado = "";
                $flag = false;
                $flag_uf = false;
                $uf_extenso = "";
                $ufs = array('SC','PR','RS')
            @endphp

                @foreach($dados as $key => $noticia)

                    

                    @php 
                        $noticia->uf = (in_array($noticia->uf, $ufs)) ? $noticia->uf : "Nacional";
                    @endphp
                
                    @switch($noticia->uf)
                        @case('SC')
                            @php
                                $uf_extenso = 'Santa Catarina';
                            @endphp
                        @break
                        @case('PR')
                            @php
                                $uf_extenso = 'Paraná';
                            @endphp
                        @break
                        @case('RS')
                            @php
                                $uf_extenso = 'Rio Grande do Sul';
                            @endphp
                        @break
                        @case('Nacional')
                            @php
                                $uf_extenso = 'Nacional';
                            @endphp
                        @break
                    @endswitch

                    @if($noticia->uf != $uf)
                        <div class="px-3 py-2 mb-3" style="">
                            <div style="text-transform: uppercase; font-weight: 600; border-bottom: 1px solid #52c9ff; margin-bottom: 10px;">
                                <div style="">
                                <table style="margin-left: -10px;">
                                    <tr>
                                    <td><img class="icone" style="margin-left: -10px;" src="https://boletim.studioclipagem.com.br/img/map.png"></td>
                                    <td><p style="color: #03a9f4;">{!! $uf_extenso !!}</p></td>
                                    </tr>
                                </table> 
                                </div>
                            </div>
                        @php
                            $flag_uf = true;
                        @endphp
                    @endif
                                       
                                            

                        @if($noticia->clipagem != $tipo or($noticia->clipagem == $tipo and $noticia->uf != $uf))
                            @switch($noticia->clipagem)
                                @case('web')
                                    @php
                                        $tipo_formatado = 'Web';
                                        $icone = 'web';
                                    @endphp
                                @break
                                @case('tv')
                                    @php
                                        $tipo_formatado = 'TV';
                                        $icone = 'tv';
                                    @endphp
                                @break
                                @case('radio')
                                    @php
                                        $tipo_formatado = 'Rádio';
                                        $icone = 'radio';
                                    @endphp
                                @break
                                @case('jornal')
                                    @php
                                        $tipo_formatado = 'Jornal';
                                        $icone = 'jornal';
                                    @endphp
                                @break
                                @default
                                    @php
                                        $tipo_formatado = 'Clipagens';
                                    @endphp
                                @break                                    
                            @endswitch
                            <div style="text-transform: uppercase; font-weight: 600; padding: 1px 8px;">
                            <table>
                                <tr>
                                <td><img class="icone" src="https://studiosocial.app/img/icone_{{ $icone }}.png"></td>
                                <td><p>{!! $tipo_formatado !!}</p></td>
                                </tr>
                            </table> 
                            </div>
                        @endif

                        @if($noticia->clipagem == 'tv')
                            
                            <div style="border-bottom: 1px solid #e3e3e3; margin-bottom: 10px; padding-bottom: 10px; line-height: 17px; padding-left: 9px;">
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Data:</strong> {{ date('d/m/Y', strtotime($noticia->data)) }}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Emissora:</strong> {{ $noticia->INFO1 }}</p>
                                @if($noticia->INFO2)
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Programa:</strong> {{ $noticia->INFO2 }}</p>
                                @endif
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Duração:</strong> {{ gmdate("H:i:s", $noticia->segundos)}}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Sinopse:</strong> {!! $sinopse = strip_tags(str_replace('Sinopse 1 - ', '', $noticia->sinopse)) !!}</p>

                                @php 
                                    $file_headers = @get_headers(env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.mp4');
                                @endphp
                                @if(!(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'))
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Link:</strong> <a href="{{ env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.mp4' }}" download>Assista</a></p>
                                @endif
                            </div>

                        @elseif($noticia->clipagem == 'radio')

                            <div style="border-bottom: 1px solid #e3e3e3; margin-bottom: 10px; padding-bottom: 10px; line-height: 17px; padding-left: 9px;">
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Data:</strong> {{ date('d/m/Y', strtotime($noticia->data)) }}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Emissora:</strong> {{ $noticia->INFO1 }}</p>
                                @if($noticia->INFO2)
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Programa:</strong> {{ $noticia->INFO2 }}</p>
                                @endif
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Duração:</strong> {{ gmdate("H:i:s", $noticia->segundos)}}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Sinopse:</strong> {!! $sinopse = strip_tags(str_replace('Sinopse 1 - ', '', $noticia->sinopse)) !!}</p>

                                @php 
                                    $file_headers = @get_headers(env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.mp3');
                                @endphp
                                @if(!(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'))
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Link:</strong> <a href="{{ env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.mp3' }}" download>Ouça</a></p>
                                @endif
                            </div>
                        
                        @elseif($noticia->clipagem == 'web')

                            <div style="border-bottom: 1px solid #e3e3e3; margin-bottom: 10px; padding-bottom: 10px; line-height: 17px; padding-left: 9px;">
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>{{ $noticia->titulo }}</strong> </p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Data:</strong> {{ date('d/m/Y', strtotime($noticia->data)) }}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Veículo:</strong> {{ $noticia->INFO1 }}</p>
                                @if($noticia->INFO2)
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Seção:</strong> {{ $noticia->INFO2 }}</p>
                                @endif
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Sinopse:</strong> {!! $sinopse = strip_tags(str_replace('Sinopse 1 - ', '', $noticia->sinopse)) !!}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Link:</strong><a href="{{ $noticia->link }}" target="_BLANK"> Acesse</a></p>

                                @php 
                                    $file_headers = @get_headers($noticia->url);
                                @endphp
                                @if(!(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'))
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Print:</strong> <a href="{{ $noticia->url }}" target="BLANK" download>Veja</a></p>
                                @endif
                            </div>                            

                        @else

                            <div style="border-bottom: 1px solid #e3e3e3; margin-bottom: 10px; padding-bottom: 10px; line-height: 17px; padding-left: 9px;">
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>{{ $noticia->titulo }}</strong></p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Data:</strong> {{ date('d/m/Y', strtotime($noticia->data)) }}</p>
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Veículo:</strong> {{ $noticia->INFO1 }}</p>
                                @if($noticia->INFO2)
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Seção:</strong> {{ $noticia->INFO2 }}</p>
                                @endif
                                <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Sinopse:</strong> {!! $sinopse = strip_tags(str_replace('Sinopse 1 - ', '', $noticia->sinopse)) !!}</p>

                                @php 
                                    $file_headers = @get_headers($noticia->url);
                                @endphp
                                @if(!(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'))
                                    <p style="margin-bottom: 0px; margin-top: 0px;"><strong>Link:</strong> <a href="{{ $noticia->url }}" download>Veja</a></p>
                                @endif
                            </div>
                            
                        @endif

                        @php
                            $tipo = $noticia->clipagem;
                            $uf = $noticia->uf;
                        @endphp

                @endforeach
            </td>
        </tr>
    </table>
    </div> 
  </body>
</html>