<template>
    <div>
        <h2>Danh sách đơn hàng</h2>
        <table id="orderTable" class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và tên</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
    
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { nextTick, onMounted, ref } from 'vue';
import AdminApi from '../../../config';
import $ from 'jquery';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import { useRouter } from 'vue-router';

const router = useRouter();
const getData = async () => {

    await nextTick(() => {
        if (!$.fn.DataTable.isDataTable('#orderTable')) {
            const table = $('#orderTable').DataTable({
                info: true,
                responsive: true,
                searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: (data, callback) => {
                    AdminApi.get(`/orders?search=${data.search.value}&start=${data.start}&length=${data.length}&order_column=${data.order[0].column}&order_dir=${data.order[0].dir}&draw=${data.draw}`)
                        .then(response => {
                            callback({
                                draw: response.data.draw,
                                recordsTotal: response.data.recordTotal,
                                recordsFiltered: response.data.recordFiltered,
                                data: response.data.data
                            })
                        }).catch(error => console.error('Có lỗi xảy ra' + error)
                        )
                }, 
                columns: [
                    {data: 'id'},
                    {data: 'full_name'},
                    {data: 'phone_number'},
                    {data: 'address'},
                    {data: 'status'},
                    {data: 'id', 
                        render: (data) =>  `
                        <button class='btn btn-secondary btn-detail' data-id=${data}>Chi tiết</button>
                        `
                    },
                ],
                language: {
                    paginate: { previous: "Trước", next: "Tiếp" },
                    search: "Tìm kiếm",
                    lengthMenu: "Hiển thị _MENU_ sản phẩm",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ sản phẩm"
                }
            })
            $('#orderTable tbody').on('click', '.btn-detail', function(){
                router.push('order-detail/'  + $(this).data('id'));                    
            })
        }
    })
    // try {
    //     const { data } = await AdminApi.get('orders');
    //     orders.value = data.orders.data

    // } catch (error) {
    //     alert(error.response.data.message)
    // }

}
onMounted(() => {
    getData();
})
</script>

<style lang="scss" scoped></style>