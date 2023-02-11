@extends('Layouts/dashboardLayout')

@section('content-section')

<style>
    div#social-links ui li{
        display: inline-block;
    }

    div#social-links ui li a{
        padding: 10px;
        boarder:1px solid #ccc;
        margin: 1px;
        front-size:30px;
        color:#222;
         background-color: #ccc
    }
</style>

{!! $shareComponent !!}

<h6 style ="cursor:pointer;" data-code ="{{ Auth::user()->referral_code}}" class ="copy"><span class="fa fa-copy"></span> Copy Referral Link </h6>
    <h2 class="mb-4"style = "float:left">Dashboard</h2>
    <h2 class="mb-4"style = "float:right">{{ $networkCount*10}}  Points</h2>

    <table>
        <thead>
            <tr>
                <th>s.no</th>
                <th>Name</th>
                <th>Email</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            @if(count($networkData) > 0)
            @php $x = 1;@endphp
                @foreach($networkData as a $network)
                <tr>
                    <td>{{$x++}}</td>
                    <td>{{$network->user->name}}</td>
                    <td>{{$network->user->email}}</td>
                    <td>
                        @if($network->user->is_verified ==0)
                        <b style= "color:red;">Un Verified </b>
                        @else
                        <b style= "color:green;">Verified </b>
                        @endif
                    </td>
                </tr>

            @endforeach
                
        @else
            <tr>
                <th colspan="4">No Referral Found</th>
            </tr>
                
            @endif
        </tbody>
    </table>
    <script>
        $(document).ready(function(){
            $('copy').click(function(){
                $(this).parent().prepend('<span class ="copied_text">Copied</span>');


                var code =$(this).attr('data-code');
                var url = "{{ URL::to('/')}}/referral-register?ref="+code;

                var $temp =$("<input>");
                $("body").append($temp);
                $temp.val(url).select();
                document.execCommand("copy");
                $temp.remove();

                setTimeout(() => {
                    $('.copied_text')
                    
                }, 2000);
            });

        });
    </script>
    
@endsection