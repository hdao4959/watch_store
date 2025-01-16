<template>
    <div class="order-container">
        <h2 class="title">Chi tiết đơn hàng</h2>
        <table class="order-table">
            <tbody>
                <tr>
                    <td class="label">Số tiền thanh toán:</td>
                    <td class="value">{{ formatCurrency(route.query.vnp_Amount) }}</td>
                </tr>
                <tr>
                    <td class="label">Ngân hàng:</td>
                    <td class="value">{{ route.query.vnp_BankCode }}</td>
                </tr>
                <tr>
                    <td class="label">Mã giao dịch ngân hàng:</td>
                    <td class="value">{{ route.query.vnp_BankTranNo }}</td>
                </tr>
                <tr>
                    <td class="label">Loại thẻ:</td>
                    <td class="value">{{ route.query.vnp_CardType }}</td>
                </tr>
                <tr>
                    <td class="label">Thông tin đơn hàng:</td>
                    <td class="value">{{ route.query.vnp_OrderInfo }}</td>
                </tr>
                <tr>
                    <td class="label">Ngày thanh toán:</td>
                    <td class="value">{{ formatDate(route.query.vnp_PayDate) }}</td>
                </tr>
                <tr>
                    <td class="label">Trạng thái giao dịch:</td>
                    <td
                        :class="{ 'success': route.query.vnp_TransactionStatus === '00', 'error': route.query.vnp_TransactionStatus !== '00'}">
                        {{ getStatus(route.query.vnp_TransactionStatus) }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Mã giao dịch:</td>
                    <td class="value">{{ route.query.vnp_TransactionNo }}</td>
                </tr>
                <tr>
                    <td class="label">Mã tham chiếu:</td>
                    <td class="value">{{ route.query.vnp_TxnRef }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { useRoute } from "vue-router";

// Sử dụng Vue Router để lấy query params
const route = useRoute();

// Hàm định dạng tiền tệ
function formatCurrency(amount) {
    if (!amount) return "Không có dữ liệu";
    return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(amount / 100);
}

// Hàm format ngày tháng từ định dạng "yyyyMMddHHmmss"
function formatDate(dateString) {
    if (!dateString) return "Không có dữ liệu";
    const year = dateString.substring(0, 4);
    const month = dateString.substring(4, 6);
    const day = dateString.substring(6, 8);
    const hour = dateString.substring(8, 10);
    const minute = dateString.substring(10, 12);
    const second = dateString.substring(12, 14);
    return `${day}/${month}/${year} ${hour}:${minute}:${second}`;
}

// Hàm hiển thị trạng thái giao dịch
function getStatus(statusCode) {
    if(statusCode === "00"){
        sessionStorage.removeItem('cart')
    }
    return statusCode === "00" ? "Thành công" : "Thất bại";
}
</script>

<style lang="scss" scoped>
.order-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: #fefefe;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;

    .title {
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.8rem;
        color: #333;
        font-weight: bold;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;

        tbody tr {
            border-bottom: 1px solid #eee;
        }

        td {
            padding: 12px 15px;
            text-align: left;
            font-size: 1rem;
            color: #555;
        }

        .label {
            font-weight: bold;
            color: #666;
            background-color: #f8f9fa;
        }

        .value {
            text-align: right;
            color: #444;
        }

        .success {
            color: #28a745;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
            font-weight: bold;
        }
    }
}
</style>