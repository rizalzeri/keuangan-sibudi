 <table class="table table-bordered">
     <thead>
         <tr>
             <th>
                 No
             </th>
             <th>
                 Pihak Kerja Sama
             </th>
             <th>
                 Deskripsi Bentuk Kerjasama
             </th>
             <th>
                 Output Kerjasama
             </th>

         </tr>

     </thead>
     <tbody>
         @php
             $i = 1;
         @endphp
         @foreach ($kerjasamas as $kerjasama)
             <tr>
                 <td class="text-center">
                     {{ $i++ }}
                 </td>
                 <td>
                     {{ $kerjasama->pihak }}
                 </td>
                 <td>
                     {{ $kerjasama->deskripsi }}
                 </td>
                 <td>
                     {{ $kerjasama->output }}
                 </td>

             </tr>
         @endforeach

         @if (count($kerjasamas) <= 0)
             <tr class="text-center">
                 <td colspan="4">Data Kerjasama Kosong</td>
             </tr>
         @endif
     </tbody>
 </table>
