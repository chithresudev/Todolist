
## TODO List Application

Run Cmd : php artisan migrate

<p> Task Create : </p>

 API (Post method): http://192.168.1.239:8082/api/task/create <br>
  Field : title, description, due_date

<p> Sub Task Create : </p>

 API (Post method): http://192.168.1.239:8082/api/task/{task_id}/create-sub-task <br>
  Field : title, description, due_date

<p> States Updated (completed or pending): </p>

API (Post method): http://192.168.1.239:8082/api/task/{task_id}/states-update <br>
Field : states(completed or pending)

<p> Task Soft Delete : </p> <br>

API (Post method): http://192.168.1.239:8082/api/task/{task_id}/remove <br>

<p> Show all the Task and Sub task (only pending) orderby ascending order on due_date : </p>

API (GET method): http://192.168.1.239:8082/api/task <br>

<p> Search Task on title </p>
 
API (GET method): http://192.168.1.239:8082/api/task <br>
Search terms : search=title&value=test

<p> Filter Task Today, Week, Next Week </p>

API (GET method): http://192.168.1.239:8082/api/task <br>
Filter terms : filter=today (or) this_week (or) next_week

<p> Soft Delete Scheduled </p> 
  namespace App\Console\Kernel.php Added..
