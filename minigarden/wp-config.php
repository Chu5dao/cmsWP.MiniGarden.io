<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'epiz_27371917_taovang' );

/** Username của database */
define( 'DB_USER', 'root' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', '' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'm7=H;67LU7Mvgq[pH0z5s=cqGM6OLSZyO=BN74keK#_%pzfZop$8ah}|=_MDo8/|' );
define( 'SECURE_AUTH_KEY',  'P^J.X$Uhj*T=5_/38r+)05xMks/=mNrJV&v)wXm/Zx-uQ0SmkM|0X-M*Y!;^4<6Z' );
define( 'LOGGED_IN_KEY',    '.!N2c!Og5$cjT@+u4wvBz!*r+0MgGX)h#u)uMQ;^wF~UDmpKDJZA3_@ND4_enhl/' );
define( 'NONCE_KEY',        '08<!1|M35^h.~dG@zBgh3zUIQPN5f-shS(V[{M)]Ei.PmE>}j7<]qfl>H-x/i^#<' );
define( 'AUTH_SALT',        '-7^`I,4#u)2J6y>7vkwq%|!$NZiWp}c=HZ+}m@AlB)<F]vCulW7R;~V1DJn?oO3r' );
define( 'SECURE_AUTH_SALT', ';!(D72zfpU<<i+`VYl$+TdR?<[XSo_,@95[j- S){jsM)6t3qK`-wRn(Zr;#AZ8U' );
define( 'LOGGED_IN_SALT',   's,9GDjf(kG)Ped >Z$si#U0=+B9<xzk;&nz?:EK@l4B3sgjxpGzPuS6op0Q3wN>j' );
define( 'NONCE_SALT',       'K(|v@R>rc?Wa0@v4c{[69__b]*KJ$/Kv*n@c+y[|cc8(o,}7#ed5|~W4@L5+a(.|' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
