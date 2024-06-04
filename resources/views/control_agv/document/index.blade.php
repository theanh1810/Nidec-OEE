@extends('layouts.main')

@section('content')

<style type="text/css">
a {
	color: black;
}

.document-text {
	font-family: Times New Roman;
}

html {
	scroll-behavior: smooth;
}
</style>
<div class="container-fluid document-text">
	<div class="row justify-content-right">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
          		</div>
		        <div class="card-body">
		          	<h1 style="text-align: right; color: skyblue;">HƯỚNG DẪN VẬN HÀNH PHẦN MỀM SHIV</h1>
		          	<hr>
		          	<div class="sti">
		          		<p ><strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;border:none windowtext 1.0pt;padding:0cm;'>Công ty TNHH DV&TM Giải pháp kỹ thuật Công nghiệp Việt Nam</span></em></strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;'>(Solutions Technology Industry Viet Nam Co., Ltd) được thành lập bởi một nhóm các kỹ sư trong lĩnh vực tự động hoá và công nghệ thông tin. Chúng tôi là đơn vị tiên phong trong lĩnh vực cung cấp giải pháp tự động hoá doanh nghiệp, thiết bị máy móc phụ trợ sản xuất và dịch vụ thi công, bảo dưỡng nhà xưởng.</span></em></p>
		          		<p ><strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;border:none windowtext 1.0pt;padding:0cm;'>Với thế mạnh của công ty STI-VIETNAM</span></em></strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;'> là đưa ra giải pháp tự động hoá cho nhà máy, nhằm tăng năng suất, chất lượng và giảm chi phí sản xuất. Với đội ngũ nhân viên kỹ thuật cao, giàu kinh nghiệm có thể đảm nhận từ khâu thiết kế đến khâu chế tạo theo yêu cầu của khách hàng.</span></em></p>
		          		<p ><strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;border:none windowtext 1.0pt;padding:0cm;'>Giá trị cốt lõi của công ty:</span></em></strong></p>
		          		<ul style="list-style-type: disc;margin-left:0cmundefined;">
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Trí tuệ, sáng tạo là nền móng của công ty</span></em></li>
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Con người là tài sản vô giá của công ty</span></em></li>
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Sự đoàn kết và tính chuyên nghiệp là phương pháp làm việc của công ty</span></em></li>
		          		</ul>
		          		<p><strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;border:none windowtext 1.0pt;padding:0cm;'>Phương châm hoạt động của STI-VIETNAM:</span></em></strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;'> Sự hài lòng của khách hàng là giá trị và sự sống còn của công ty”. Chính vì vậy điều quan trọng hàng đầu trong chiến lược kinh doanh của chúng tôi là cam kết mang lại hiệu quả tối ưu cho khách hàng bằng những giải pháp công nghệ hữu ích và hơn hết là bằng chất lượng dịch vụ được thể hiện trong nguyên tắc phục vụ khách hàng.</span></em></p>
		          		<p><strong><em><span style='font-size:19px;line-height:150%;color:#2C2D2D;border:none windowtext 1.0pt;padding:0cm;'>Quan điểm thực hiện các dự án của chúng tôi:</span></em></strong></p>
		          		<ul style="list-style-type: disc;margin-left:0cmundefined;">
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Luôn hoàn thành các dự án đúng thời hạn và chất lượng</span></em></li>
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Luôn sẵn hàng hỗ trợ khách hàng khi có yêu cầu</span></em></li>
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Áp dụng và tuân thủ triệt để các quy trình và phương pháp quản trị dự án</span></em></li>
		          			<li><em><span style='line-height:150%;font-size:14.0pt;color:#2C2D2D;'>Áp dụng công nghệ mới và phù hợp với điều kiện và yêu cầu khách hàng</span></em></li>
		          		</ul>
		          	</div>
		          	<hr>
		          	<!-- Mục Lục -->
		          	<h2>Mục Lục</h2>
		          	<p style="color:black;">
		          		<strong>
		          			<span>HỆ THỐNG SHIV</span><br>
		          			<span><a href="#I">I. Giới thiệu phần mềm</a></span><br>
		          			<span><a href="#II">II. Giới thiệt về hệ thống</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#II_1">2.1 Xuất Nhập Của Kho Dập và Đúc Tiện</a></span><br>
		          			<span><a href="#III">III. Hướng Dẫn Gọi AGV Trên Phần Mềm</a></span><br>
		          			<span style=';margin-left: 10px;'><a href="#III_1">3.1 Gọi AGV Mang Giá Từ Kho Ra Và Đưa Hàng Về</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#III_2">3.2 Nhập Kho AGV</a></span><br>
		          			<span><a href="#IV">IV. Cách Kiểm Tra Nếu Gọi Hàng Không Thành Công</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#IV_1">4.1: Kiểm Tra Vị Trí Line Có Đang Hoạt Động Hay Không</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#IV_2">4.2: Kiểm Tra Mã Thùng Có Tồn Tại Và Đúng Mã Sản Phẩm Hay Không</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#IV_3">4.3: Nếu Mã Thùng Không Đúng Với Sản Phẩm Người Thao Tác Click Vào Sửa</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#IV_4">4.4: Kiểm Tra Thông Tin Kho Xem Trong Kho Có Hàng Hay Không</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#IV_5">4.5: Kiểm Tra Trong Kho Có Giá Thùng Trống Không</a></span><br>
		          			<span><a href="#V">V. Gọi AGV 1 chiều</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#V_1">5.1: Chỉ Gọi AGV Mang Giá Trống Từ Kho Ra</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#V_2">5.2: Gọi Giá Hàng Từ Kho Ra line Sản Xuất</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#V_3">5.3: Gọi AGV Trả Giá Hàng Về Kho</a></span><br>
		          			<span><a href="#VI">VI. Thêm Sản Phẩm Mới</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#VI_1">6.1: Thêm Mã Sản Phẩm</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#VI_2">6.2: Thêm Thùng Hàng</a></span><br>
		          			<span><a href="#VII">VII. Xuất Nhập Hàng Của Kho Frame Và Frame-Assy</a></span><br>
		          			<span><a href="#VIII">VIII. Chuyển  Kho Frame <i class="fas fa-arrows-alt-h"></i> Frame – Assy</a></span><br>
		          			<span style='margin-left: 10px;'><a href="#VIII_1">8.1: Chuyển Hàng tử kho Frame sang Frame – Assy</a></span><br>
		          			<span><a href="#IX">IX: Chuyển Hàng Trục MM – SM</a></span><br>
		          			<span><a href="#X">X: Lập Kế Hoạch Sản Xuất</a></span><br>
		          			<span><a href="#XI">XI: Giới Hạn Sản Phẩm</a></span><br>
		          		</strong>
		          	</p>
		          	<hr>
		          	<!-- Giới thiệu phần mềm -->
		          	<div class="recomend_app">
		          		<!-- Giới thiệu phần mềm -->
		          		<p><span><strong>Hệ Thống SHIV</strong></span></p>
		          		<p id="I"><span><strong>Giới Thiệu Phần Mềm</strong></span></p>
		          		<p><strong>- Bước 1 :</strong> Trên màn hình máy tính đã được cài đặt google chrome người thao tác click vào Biểu tượng chrome</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture1.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 1 :  Màn hình Desktop</em></p>
		          		<p><strong>- Bước 2: </strong><span>Sau khi vào google chrome Người thao tác nhập địa chỉ 10.10.1.2:8686 ( link dẫn đến phần mềm )</span></p>
		          		<p><strong>- Bước 3: </strong><span>Sau khi dẫn đến đường dẫn sẽ bắt người thao tác đăng nhập người thao tác sẽ nhập User : Admin4 Và Pass: 123456</span></p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture2.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 2: Đăng nhập vào phần mềm</em></p>
		          		<ul>
		          			<li>Ở giao diện màn hình chính người thao tác có thể theo dõi được Tình Trạng của Kho</li>
		          			<br>
		          			<p style="text-align: center;">
		          				<img src="{{asset('uploads/image_tutorial/Picture3.png')}}" style="width: 60%;height: 400px;">
		          			</p>
		          			<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 3: Giao Diện sau khi Đăng Nhập</em></p>
		          		</ul>
		          	</div>
		          	<!-- Giới thiệu hệ thống -->
		          	<div class="recommend">
		          		<p id="II"><strong>II. Giới Thiệu Về Hệ Thống</strong></p>
		          		<ul>
		          			<li>Hệ thống SHIV là hệ thống hoạt động tự đông với các kho được setup trong toàn bộ nhà máy. Hàng được xuất nhập từ động từ các line vào các kho thành phẩm và sẽ xuất từ các kho đến các line sản xuất hoặc sẽ xuất hang và nhập hang giữa các kho với nhau.</li>
		          		</ul>
		          		<p id="II_1"><span><strong >2.1 Xuất Nhập Của Kho Dập và Đúc Tiện</strong></span></p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture7.png')}}" style="width: 60%;height: 350px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 4: Quy Trình Hoạt Động Của Kho Dập Và Đúc Tiện</em></p>
		          		<ul>
		          			<li>Kho Dập</li>
		          			<p>- Nơi lưu trữ hàng của kho dập sau khi gia công xong và cung cấp hàng đến line đúc</p>
		          			<li>Kho Đúc Tiện</li>
		          			<p>- Nới lưu trữ hàng của kho đúc và xuất kho đến line tiện</p>
		          			<li>Line Dập</li>
		          			<p>- Hàng sẽ được sản xuất tại line dập <i class="fas fa-arrow-right"></i> Nếu là rotor sẽ được nhập hang vào kho dập còn nếu hang là stato sẽ được nhập trực tiếp sang bên kho đúc.</p>
		          			<li>Line Đúc</li>
		          			<p>- Xuất Kho : Line đúc sẽ gọi hàng từ kho dập khi đó agv sẽ vào kho dập để lấy hàng mang vào line đúc để sản xuất và trả về kho dập là giá trống hoặc có hàng</p>
		          			<p>- Nhập Kho : Line đúc sẽ nhập hang vào kho đúc tiện kho đó agv sẽ lấy giá trống từ kho đúc tiện và mang giá trống trao hang đổi kệ trong line đúc để mang hàng sản xuất trong line đúc nhập hàng vào kho</p>
		          			<li>Line Tiện</li>
		          			<p>- Khi line tiện gọi hàng AGV sẽ lấy hàng từ kho đúc tiện sau đó mang ra line tiện để sản xuất và sau khi trao hàng đổi kệ thì sẽ trả về kho là giá trống hoặc có hàng</p>
		          		</ul>
		          	</div>
		          	<!-- Hướng Dẫn Gọi AGV Trên Phần Mềm  -->
		          	<div class="instruction">
		          		<p id="III"><strong>III. Hướng Dẫn Gọi AGV Trên Phần Mềm</strong></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Sau khi đăng nhập người thao tác click vào phần Hệ Thống Vận Chuyển <i class="fas fa-arrow-right"></i> Thêm Mới Lệnh AGV</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture8.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 5: Tạo Lệnh</em></p>

		          		<p><span><strong>- Bước 2 : </strong></span>Sau Khi Click Vào Thêm Mới Lệnh AGV Sẽ xuất hiện giao diện</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture9.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 6: Trang Chủ Tạo Lệnh</em></p>

		          		<p id="III_1"><span><strong>3.1 : Gọi AGV Mang Giá Từ Kho Ra Và Đưa Hàng Về</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Người Thao Tác Click Vào Nhập Vị trí rồi tìm kiếm Sau đó người thao tác sẽ chọn phần Nhập Kho và Xuất Kho để tạo lệnh</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture10.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 7: Tìm Mã Vị trí Và Chọn Để Tạo Lệnh</em></p>
		          		<p><span><strong>Note : </strong></span>Mã vị trí đã được setup tại tất cả các line sản xuất. đối với các line sản xuất có 2 vị trí xuất nhập hàng thì vị trí xuất nhập hàng bên trái sẽ được dán phía bên trái màn hình và vị trí bên phải sẽ được dán phía bên phải màn hình</p>

		          		<p><span><strong>- Bước 2 : </strong></span>Sau khi tìm mã vị trí và chọn kiểu nhập người thao tác sẽ Scan PID gọi đến và Mã Thùng Trả Về</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture11.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 8: Scan mã PID Và Mã Thùng</em></p>


		          		<p><span><strong>- Bước 3 : </strong></span>Sau khi Scan mã PID Và Mã thùng Người thao tác lưu ý sẽ click vào vị trí  giá trống nếu muốn lấy giá trống từ kho ra và Click vào có Hàng nếu lấy hàng từ kho ra</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture11.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 9: Thùng Có Hàng hoặc Thùng Trống hoặc Kệ Hàng Trống</em></p>
		          		<p><span><strong>Note : </strong></span>Khi Người Thao Tác Scan Mã vị trí và PID và Mã Thùng thì khi bắn scan người thao tác tắt Caps lock đi thì khi scan sẽ hiện chữ hoa nếu chữ thường sẽ bị lỗi</p>

		          		<p><span><strong>- Bước 4 : </strong></span>Sau Khi làm đúng và đủ 3 bước trên người thao tác click vào Tạo Lệnh</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture10.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 10: Tạo Lệnh Gọi AGV</em></p>
		          		<p><strong>Note:</strong></p>
		          		<ul>
		          			<p>- Sau khi gọi AGV nếu thành công sẽ hiện dòng chữ Xanh “ Thành Công”</p>
		          			<p>- Nếu bị lỗi sẽ Hiện dòng chữ màu đỏ. Nếu không thành công sẽ báo theo các lỗi khác nhau vì vậy khi bị lỗi sẽ kiểm tra xem lỗi ở đâu</p>
		          		</ul>

		          		<p id="III_2"><span><strong>3.2 : Nhập Kho AGV </strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Sau khi agv đến vị trí line sản xuất và đem hàng từ kho vào và hạ xuống agv vào nâng hàng để mang vào kho lúc này người thao tác Xác nhận để AGV nâng hàng Về <i class="fas fa-arrow-right"></i> ở mục tạo lệnh Người thao tác Click Vào <span><strong>Nhập Vị Trí </strong></span> ( Là mã đã được dán ở các máy tính ở line sản xuất) <i class="fas fa-arrow-right"></i> Chọn Nhập Kho</p>
		          		<p><span><strong>- Bước 2 : </strong></span> ( Ở mỗi kệ đều đã được dán mã giá ) <i class="fas fa-arrow-right"></i> Người thao tác scan mã kệ</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture12.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 11: Scan Mã Giá</em></p>

		          		<p><span><strong>- Bước 3 : </strong></span>Scan mã thùng trả về kho </p>
		          		<ul>
		          			<p>
		          				- Sau Khi Scan xong mã thùng người thao tác nhập số lượng vào máy Scan
		          			</p>
		          		</ul>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture12.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 12: Scan mã thùng</em></p>
                        <p><span><strong>Note : </strong></span>Có thể nhập bằng tay bằng cách Thêm Kho Kệ Hàng</p>

		          		<p><span><strong>- Bước 4 : </strong></span>Ở trên phần mềm mặc định là 3 vị trí thùng vì vậy nếu trong trường hợp người thao tác dung hơn 3 thùng thì người thao tác click vào <span><strong>thêm</strong></span> để thêm số lượng thùng trả về hoặc nếu người thao tác trả về ít hơn 3 thùng thì người thao tác click vào <span><strong>xóa</strong></span> những vị trí không cần</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture12.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 13: Thêm Hoặc Xóa Vị Trí Nhập Kho</em></p>

		          		<p><span><strong>- Bước 5 : </strong></span>Click Vào <span><strong>"Nhập Kho"</strong></span> ( Sau khi click vào nhập kho AGV sẽ mang hàng về kho )</p>
		          	</div>
		          	<!-- Cách Kiểm Tra Nếu Gọi Hàng Không Thành Công -->
		          	<div class="check-hang">
		          		<p id="IV"><strong>IV: Cách Kiểm Tra Nếu Gọi Hàng Không Thành Công</strong></p>
		          		<p id="IV_1"><strong>4.1: Kiểm Tra Xem Vị Trí Line Có Đang Gọi Hay Không </strong></p>
		          		<p>- Click Vào Mã Vị Trí Sau Đó Scan Vị Trí Trên Line Sau Đó Click Vào Tìm Kiếm. Nếu Line Sản Xuất Đang Gọi Hàng Thì sẽ Không Gọi Tiếp Được Nữa. Vì Vậy Đợi Sau Khi Lệnh Đang Gọi Hoàn Thành Sẽ Gọi Lệnh Tiếp Theo. </p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture17.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 14: Kiểm Tra Vị Trí Line</em></p>
		          		<ul><li>Nếu mà line không có lệnh gọi thì kiểm tra tiếp bước tiếp</li></ul>
		          		<p id="IV_2"><strong>4.2: Kiểm Tra Mã Thùng Có Tồn Tại Và Đúng Mã Sản Phẩm Hay Không</strong></p>
		          		<p><span><strong>- Bước 1 : </strong></span>: Click vào cài đặt và vào Thùng</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture18.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 15: Kiểm Tra Mã Thùng</em></p>

		          		<p><span><strong>- Bước 2 : </strong></span>: Sau đó giao diện xuất hiện. Người thao tác nhấn Ctrl + F để tìm kiếm mã sản phẩm. Sau đó người thao tác ghi mã sản phẩm phần mềm sẽ tự tìm kiếm</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture19.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 16: Tìm Kiếm Mã Thùng</em></p>
		          		<ul>
		          			<li>
		          				Nếu mã thùng tìm kiếm đúng với mã PID như hình 12 thì người thao tác chuyển sang bước tiếp theo
		          			</li>
		          		</ul>
		          		<p><span><strong>Note : </strong></span>Nếu mã thùng chưa được them vào hệ thống thì người thao tác sẽ thêm vào hệ thống</p>

		          		<p id="IV_3"><span><strong>4.3 : Nếu Mã Thùng Không Đúng Với Sản Phẩm Người Thao Tác Click Vào Sửa</strong></span></p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture20.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 17: Click Vào Sửa Sản Phẩm</em></p>

		          		<ul>
		          			<li>Giao Diện Xuất Hiện Người Thao Tác click vào Ký Hiệu Sản Phẩm và tìm đến mã PID của mã thùng </li>
		          		</ul>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture21.png')}}" style="width: 55%;height: 145px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 18 : Sửa Sản Phẩm</em></p>
		          		<p><span><strong>Note : </strong></span>Sau Khi sửa xong Ký Hiệu Sản Phẩm và thùng giống nhau người thao tác click vào Lưu</p>

		          		<p id="IV_4"><span><strong>4.4 : Kiểm Tra Thông Tin Kho Xem Trong Kho Có Hàng Hay Không</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click Vào Hệ Thống Kho <i class="fas fa-arrow-right"></i> Kho Lưu Trữ</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture22.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 19: Click vào Kho Lưu Trữ</em></p>

		          		<p><span><strong>- Bước 2 : </strong></span>Chọn Kho cần Kiểm Tra Thông Tin</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture24.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 20: Thông Tin Kho</em></p>
		          		<p><span><strong>Note : </strong></span>Nếu trong kho có hàng người thao tác sẽ làm bước tiếp theo</p>

		          		<p id="IV_5"><span><strong>4.5 : Kiểm Tra Trong Kho Có Giá Thùng Trống Không</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click Vào Kho Lưu Trữ</p>
		          		<p><span><strong>- Bước 2 : </strong></span>Click Vào Kho cần tìm sau đó sẽ hiển thị những Thùng Trống bằng màu đỏ  </p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture24.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 21: Tìm Giá thùng trống</em></p>
		          		<p><span><strong>Note : </strong></span>Nếu trong kho không có giá trống thì không thể gọi được hàng vì nếu cần gọi hàng thì trong kho ít nhất phải có 1 vị trí trống</p>
		          		<p>+ Nếu không có giá trống người thao tác đợi agv khác hoàn thành lệnh để có giá trống tạo lệnh</p>
		          		<ul>
		          			<li>Nếu Người Thao Tác Làm Tất Cả Các Bước Trên Đều Đúng Mã. Không Có Lỗi Về Kho, Lỗi Mã Thùng, Mã Sản Phẩm, Vị Trí Line Không Gọi AGV Thì Báo Lại Với STI Để Xử Lý</li>
		          		</ul>
		          	</div>
		          	<!--  Gọi AGV 1 chiều -->
		          	<div class="call-agv">
		          		<p id="V"><span><strong>V. Gọi AGV 1 chiều</strong></span></p>
		          		<p id="V_1"><span><strong>5.1. Chỉ Gọi AGV Mang Giá Trống Từ Kho Ra</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click Vào Thêm Mới Lệnh AGV</p>
		          		<p><span><strong>- Bước 2 : </strong></span>Nhập Mã Vị Trí <i class="fas fa-arrow-right"></i> Chọn Xuất Kho</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture26.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 22: Mã Vị Trí Gọi</em></p>
		          		<p><span><strong>- Bước 3 : </strong></span>Scan Mã PID Gọi Đến</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture26.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 23: PID Gọi đến</em></p>
		          		<p><span><strong>- Bước 4 : </strong></span>Click Thùng Không Hoặc Thùng Hàng</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture26.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 24: Click Thùng Không Hoặc Thùng Hàng</em></p>
		          		<p><span><strong>- Bước 5 : </strong></span>Click Tạo Lệnh</p>

		          		<p id="V_2"><span><strong>5.2. Gọi Giá Hàng Từ Kho Ra line Sản Xuất</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click Vào Thêm Mới Lệnh AGV</p>
		          		<p><span><strong>- Bước 2 : </strong></span>Scan Mã Vị Trí </p>
		          		<p><span><strong>Note : </strong></span>Đối với ở khu vực frame – frame-assy nếu muốn lấy giá trống thì scan vị trí để hàng ở kho </p>
		          		<p>- Ví Dụ : Nếu kho frame-assy muốn lấy giá không ở kho Frame đem về vị trí FA-A1 thì người thao tác scan mã vị trí là FA-A1</p>
		          		<p><span><strong>- Bước 3 : </strong></span>Click vào giá trống</p>
		          		<p><span><strong>- Bước 4 : </strong></span>Click vào Tạo Lệnh</p>

		          		<p id="V_3"><span><strong>5.3. Gọi AGV Trả Giá Hàng Về Kho</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click Vào Thêm Mới Lệnh AGV</p>
		          		<p><span><strong>- Bước 2 : </strong></span>Tìm Kiếm Mã Vị Trí</p>
		          		<p><span><strong>- Bước 3 : </strong></span>Scan Mã Thùng Sản Phẩm</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture12.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 25: Mã Thùng Sản Phẩm Nhập Vào</em></p>
		          		<p><span><strong>- Bước 4 : </strong></span>Click Vào Tạo Lệnh</p>
		          		<p><span><strong>Note : </strong></span>Đối với bước gọi AGV mang hàng vào kho thì cần phải xác nhận nhập ( xem chi tiết ở mục <span><strong><a href="#III_2">3.2: Nhập Kho AGV</a></strong></span> )</p>
		          	</div>
		          	<!-- Thêm Sản Phẩm Mới -->
		          	<div class="import-new-product">
		          		<p id="VI"><span><strong>VI. Thêm Sản Phẩm Mới</strong></span></p>
		          		<p id="VI_1"><span><strong>6.1. Thêm Mã Sản Phẩm</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click Vào Cài Đặt Và Click Vào Sản Phẩm</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture30.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 26: Click Vào Thêm Sản Phẩm</em></p>
		          		<p><span><strong>- Bước 2 : </strong></span>Click vào ô tên sản phẩm gõ sản phẩm muốn thêm</p>
		          		<ul>
		          			<p>- Click vào những trường mà bạn muốn nhập và chọn</p>
		          		</ul>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture31.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 27: Thêm Sản Phẩm</em></p>
		          		<p><span><strong>- Bước 3 : </strong></span>Sau khi thêm đầy đủ click vào <span><strong>Lưu</strong></span></p>

		          		<p id="VI_2"><span><strong>6.2: Thêm Thùng Hàng</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Click vào cài đặt và click vào Thùng </p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture18.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 28: click vào thêm thùng hàng</em></p>
		          		<p><span><strong>- Bước 2 : </strong></span>Click Vào Thêm Thùng Hàng để Thêm Thùng Hàng mới</p>
		          		<ul>
		          			<p>- Click Ký Hiệu Sản Phẩm Để Chọn Mã Sản Phẩm Đúng Với Mã Thùng ( Mã Sản Phẩm Là PID Của Mã Thùng ) Nếu Sai Sẽ Gây Nên Lỗi</p>
		          			<p>- Click Vào Số Lượng Giới Hạn Và Ghi Số Lượng Tối Đa Của Thùng Sản Phẩm</p>
		          		</ul>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture33.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 29: Thêm Thùng Sản Phẩm</em></p>
		          		<p><span><strong>- Bước 3 : </strong></span>Sau Khi Thực Hiện Hết Các Bước Click Vào Lưu Để Thêm Mã Thùng</p>
		          	</div>
		          	<!-- Xuất Nhập Hàng Của Kho Frame Và Frame-Assy -->
		          	<div class="export_import_frame">
		          		<p id="VII"><span><strong>VII. Xuất Nhập Hàng Của Kho Frame Và Frame-Assy</strong></span></p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture37.png')}}" style="width: 60%;height: 300px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 30: Quy TrìnhXuất Nhập Hàng Của Kho Frame Và Frame-Assy</em></p>
		          		<ul>
		          			<li>Kho Frame</li>
		          			<p><i class="fas fa-arrow-right"></i> Nhập Vào Kho Assy quy trình sẽ nhập bằng tay và hàng sẽ quy tụ và được đưa ra tổ sơn và kho frame-assy</p>
		          			<li>Tổ Sơn</li>
		          			<p><i class="fas fa-arrow-right"></i> Xuất Kho : Khi tổ sơn gọi hàng thì AGV sẽ vào kho frame lấy hàng và mang hàng ra tổ sơn để trao hàng đổi kệ. Sau khi trao hàng đổi kệ xong AGV sẽ mang giá trống về hoặc hàng </p>
		          			<p><i class="fas fa-arrow-right"></i> Nhập Kho : Khi Tổ sơn gọi hàng nhập kho thì AGV sẽ vào kho frame assy để lấy kệ trống sau đó mang kệ trống ra tổ sơn. Và sau đó thực hiện trao hàng đổi kệ sau đó AGV sẽ đem hàng về kho frame-assy </p>
		          			<li>Kho Frame-Assy</li>
		          			<p><i class="fas fa-arrow-right"></i> Kho Frame - Assy là nơi lưu trữ hàng cuối cùng. Hàng sẽ được nhập từ tổ sơn và tư kho Frame sang. Và sẽ không xuất tự động đi chỉ xuất bằng tay</p>
		          			<p><i class="fas fa-arrow-right"></i> Nhập từ kho Frame : sau khi kho Frame - Assy gọi hàng AGV sẽ đi đến kho Frame để lấy hàng sau đó AGV sẽ mang hàng đến vị trí trống tại kho Frame - Assy
		          			</p>
		          		</ul>
		          	</div>
		          	<!-- Chuyển  Kho Frame  Frame – Assy -->
		          	<div class="switch-frame">
		          		<p id="VIII"><span><strong>VIII. Chuyển  Kho Frame <i class="fas fa-arrows-alt-h"></i> Frame – Assy</strong></span></p>
		          		<p id="VIII_1"><span><strong>8.1: Chuyển Hàng tử kho Frame sang Frame – Assy</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Sau khi đăng nhập người thao tác click vào phần Hệ Thống Vận Chuyển <i class="fas fa-arrow-right"></i> Thêm Mới Lệnh AGV</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture8.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 31: Chuyển Kho</em></p>
		          		<p><span><strong>- Bước 2 : </strong></span>Ở mục chuyển kho người thao tác lựa chọn </p>
		          		<ul>
		          			<p>- Nhập Vị Trí AGV rồi tìm kiếm</p>
		          			<p>- Chọn Chuyển Kho</p>    
		          		</ul>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture39.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 32: Chuyển Kho Frame sang Frame -assy</em></p>
		          		<p><span><strong>- Bước 3 : </strong></span>Sau khi Scan PID và Mã Thùng Nhập Kho thì bấm Tạo Lệnh để thực hiện</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture39.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 33: Tạo Lệnh</em></p>
		          		
		          	</div>
		          	<!-- Chuyển Hàng Trục MM – SM -->
		          	<div class="chuyenhang">
		          		<p id="IX"><span><strong>IX:  Chuyển Hàng Trục MM – SM</strong></span></p>
		          		<p><span><strong>- Bước 1 : </strong></span>Trên Màn Hình Deskop Người Thao Tac Click Vào Phần mềm</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture44.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 34: Clik Vào Phần Mềm</em></p>
		          		<p><span><strong>- Bước 2 : </strong></span>Click vào vị trí lựa chọn vị trí trục (Nếu vị trí máy là trục SM click vào vị trí lựa chọn SM Và Nếu vị trí máy là trục MM click vào vị trí lựa chọn MM )</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture45.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 35: Lựa chọn vị trí trục</em></p>
		          		<p><span><strong>- Bước 3 : </strong></span>Nếu Là Ở trục SM và cần hàng click vào SM Gọi Hàng</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture46.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 36: Click Vào SM gọi hàng </em></p>
		          		<p><span><strong>- Bước 4 : </strong></span>Nếu là ở trục MM và có hàng click vào MM Có Hàng</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture47.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 37: Click vào MM có hàng</em></p>
		          		<p><span><strong> Note : </strong></span>Sau Khi người thao tác click Vào MM CÓ HÀNG VÀ SM GỌI HÀNG thì ở dòng đó sẽ đổi trạng thái sang màu xanh báo là đã thành công</p>
		          		<p><span><strong>- Bước 5 : </strong></span>Sau khi Bên SM báo cần hàng Và MM báo có hàng thì hệ thống sẽ nhận và hiển thị trạng thái báo đang chuyển hàng</p>
		          		<br>
		          		<p style="text-align: center;">
		          			<img src="{{asset('uploads/image_tutorial/Picture48.png')}}" style="width: 60%;height: 400px;">
		          		</p>
		          		<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 38: Trạng Thái Lệnh AGV</em></p>
		          	</div>
					<!-- Lập Kế Hoạch Sản Xuất -->
					<div class="import-new-product">
						<p id="X"><span><strong>X. Lập Kế Hoạch Sản Xuất</strong></span></p>
						<p id="X_1"><span><strong>10.1. Thêm Kế Hoạch Sản Xuất Tổng</strong></span></p>
						<p><span><strong>- Bước 1 : </strong></span>Click Vào Kế Hoạch Sản Xuất Và Click Vào Lệnh Sản Xuất</p>
						<br>
						<p style="text-align: center;">
							<img src="{{asset('uploads/image_tutorial/Picture49.jpg')}}" style="width: 60%;height: 400px;">
						</p>
						<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 49: Danh Sách Kế Hoạch</em></p>
						<p><span><strong>- Bước 2 : </strong></span>Click Vào Thêm Mới Kế Hoạch</p>
						<ul>
							<p>- Nhập Thông Tin Của Kế Hoạch</p>
						</ul>
						<br>
						<p style="text-align: center;">
							<img src="{{asset('uploads/image_tutorial/Picture50.jpg')}}" style="width: 60%;height: 400px;">
						</p>
						<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 50: Thêm Kế Hoạch</em></p>
						<p><span><strong>- Bước 3 : </strong></span>Sau khi nhập đầy đủ click vào <span><strong>Lưu</strong></span></p>

						<p id="VI_2"><span><strong>10.2: Thêm Kế Hoạch Sản Xuất Chi Tiết</strong></span></p>
						<p><span><strong>- Bước 1 : </strong></span>Click vào chi tiết kế hoạch </p>
						<br>
						<p style="text-align: center;">
							<img src="{{asset('uploads/image_tutorial/Picture51.jpg')}}" style="width: 60%;height: 400px;">
						</p>
						<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 51: Chi Tiết Kế Hoạch Sản Xuất</em></p>
						<p><span><strong>- Bước 2.1 : </strong></span>Click Vào Thêm Mới Kế Hoạchđể thêm mới chi tiết kế hoạch sản xuất</p>
						<ul>
							<p>- Chọn Sản Phẩm Cần Sản Xuất</p>
							<p>- Chọn Máy Sản Xuất</p>
							<p>- Chọn Ca Sản Xuất Của Sản Phẩm</p>
							<p>- Nhập Số Lượng Cần Sản Xuất Của Sản Phẩm</p>
						</ul>
						<br>
						<p style="text-align: center;">
							<img src="{{asset('uploads/image_tutorial/Picture52.jpg')}}" style="width: 60%;height: 400px;">
						</p>
						<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 52: Thêm Mới Kế Hoạch Chi Tiết Thủ Công</em></p>
						<p><span><strong>- Bước 2.2 : </strong></span>Click Vào Nhập Bằng Excel để thêm mới chi tiết kế hoạch sản xuất</p>
						<ul>
							<p>- Chọn File Kế Hoạch Sản Xuất</p>
						</ul>
						<br>
						<p style="text-align: center;">
							<img src="{{asset('uploads/image_tutorial/Picture53.jpg')}}" style="width: 60%;height: 400px;">
						</p>
						<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 52: Thêm Mới Kế Hoạch Chi Tiết Nhập File</em></p>
						<ul>
							<p>- File Kế Hoạch Sản Xuất</p>
						</ul>
						<br>
						<p style="text-align: center;">
							<img src="{{asset('uploads/image_tutorial/Picture54.jpg')}}" style="width: 100%;height: 400px;">
						</p>
						<p style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'><em>Hình 52: Thêm Mới Kế Hoạch Chi Tiết Nhập File</em></p>
						<p><span><strong>- Bước 3 : </strong></span>Sau Khi Thực Hiện Hết Các Bước Click Vào Lưu Để Thêm Kế Hoạch</p>
					</div>
					<!-- Giới Hạn Sản Phẩm -->
					<div class="limit-product">
						<p id="XI"><span><strong>XI. Giới Hạn Sản Phẩm</strong></span></p>
						<div class="LK">
							<p><span><strong>- Bước 1 : </strong></span>Sau khi đăng nhập người thao tác click vào phần
								Cài Đặt <i class="fas fa-arrow-right"></i> Giới Hạn Sản Phẩm</p>
							<br>
							<p style="text-align: center;">
								<img src="{{ asset('uploads/image_tutorial/Picture55.png') }}"
									style="width: 60%;height: 400px;">
							</p>
							<p
								style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'>
								<em>Hình 55: Danh Sách Sản Phẩm Được Cài Đặt Giới Hạn</em>
							</p>
							<p><span><strong>- Bước 2 : </strong></span>Thêm Mới Giới Hạn Sản Phẩm
								<br>
							<p style="text-align: center;">
								<img src="{{ asset('uploads/image_tutorial/Picture56.png') }}"
									style="width: 60%;height: 400px;">
							</p>
							<p
								style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;text-indent:22.5pt;line-height:107%;font-size:19px;text-align:center;'>
								<em>Hình 56: Nhập Thông Tin Giới Hạn Sản Phẩm Và Lưu</em>
							</p>
							<ul>
								<li>Note : </li>
								<p>- Giới Hạn Sản Phẩm là : số lượng sản phẩm còn lại trong khay sản xuất để hệ thống tự tạo lệnh AGV mới</p>
								<p>- Chu Kỳ là : Thời Gian sản xuất được 1 sản phẩm để phần mềm có thể tính số liệu OEE 1 cách chính xác nhất</p>
							</ul>
						</div>
					</div>
		          	<!-- Hỗ trợ  -->
		          	<hr>
		          	<div class="support">
		          		<h2 >NẾU TRONG QUÁ TRÌNH THAO TÁC XẢY RA LỖI VÀ NGƯỜI THAO TÁC ĐÃ KIỂM TRA TẤT CẢ CÁC THÔNG TIN MÀ KHÔNG CÓ LỖI VUI LÒNG BÁO LẠI STI ĐỂ STI XỬ LÝ</h2>
		          	</div>
		        </div>
      		</div>
  		</div>
	</div>
</div>
@endsection
