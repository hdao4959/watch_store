<template>
    <div>
        <h2>Danh sách sản phẩm</h2>
        <router-link class="btn btn-success" to="add-product">Thêm mới</router-link>

        <table id="productTable" class="display table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, ref, nextTick } from 'vue';
import AdminApi from '../../../config';
import { url_image } from '../../../config';
import $ from 'jquery';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';

// import 'datatables.net-dt/css/jquery.dataTables.css';
import 'datatables.net';
import { useRouter } from 'vue-router';
const router = useRouter();

const getData = async () => {
    await nextTick(() => {
        if (!$.fn.DataTable.isDataTable("#productTable")) {
            const table = $('#productTable').DataTable({
                info: true,
                responsive: true,
                searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: (data, callback) => {
                    AdminApi.get(`/products?search=${data.search.value}&start=${data.start}&length=${data.length}&order_column=${data.order[0].column}&order_dir=${data.order[0].dir}&draw=${data.draw}`)
                        .then(response => {
                            callback({
                                draw: response.data.draw,
                                recordsTotal: response.data.recordsTotal,
                                recordsFiltered: response.data.recordsFiltered,
                                data: response.data.data
                            });
                        })
                        .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
                },
                columns: [
                    { data: "id", orderable: true },
                    {
                        data: "img_thumbnail",
                        render: (data) => `<img src="${url_image}${data}" width="50" alt="Sản phẩm">`,
                    },
                    { data: "name" },
                    {
                        data: 'id',
                        render: (data) => 
                        `<button class="btn btn-secondary btn-show" data-id="${data}">Show</button>
                        <button class="btn btn-warning btn-edit" data-id="${data}">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="${data}">Delete</button>`,
                        orderable: false
                    }
                ],
                language: {
                    paginate: { previous: "Trước", next: "Tiếp" },
                    search: "Tìm kiếm",
                    lengthMenu: "Hiển thị _MENU_ sản phẩm",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ sản phẩm"
                }
            });

            // Bắt sự kiện click bằng jQuery
            $('#productTable tbody').on('click', '.btn-delete', function () {
                const productId = $(this).data('id');
                onDelete(productId);
            });

            $('#productTable tbody').on('click', '.btn-show', function () {
                const productId = $(this).data('id');
                router.push({ name: 'detail-product', params: { id: productId } });
            });

            $('#productTable tbody').on('click', '.btn-edit', function () {
                const productId = $(this).data('id');
                router.push({ name: 'edit-product', params: { id: productId } });
            });
        }
    });
};


const onDelete = async (id) => {
    
    if (confirm('Bạn có chắc muốn xoá sản phẩm này không?')) {
        try {
            const response = await AdminApi.delete(`/products/${id}`);
            if (response.data.success) {
                alert(response.data.message);
                $('#productTable').DataTable().ajax.reload(); // Cập nhật lại bảng
            }
        } catch (error) {
            alert("Xóa thất bại!");
        }
    }
};


onMounted(getData);
</script>
