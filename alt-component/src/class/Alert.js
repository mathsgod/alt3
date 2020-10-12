import Swal from "sweetalert2";
class Alert {
    error(message) {
        return Swal.fire("Error", message, "error");
    }
}
export default Alert;