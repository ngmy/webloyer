<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use App\Models\Project;
use App\Models\Deployment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\Process\Process;

use Illuminate\Http\Request;

/**
 * Class DeploymentsController
 * @package App\Http\Controllers
 */
class DeploymentsController extends Controller
{
    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $project;

    /**
     * @var DeploymentForm
     */
    protected DeploymentForm $deploymentForm;

    /**
     * DeploymentsController constructor.
     * @param ProjectInterface $project
     * @param DeploymentForm $deploymentForm
     */
    public function __construct(ProjectInterface $project, DeploymentForm $deploymentForm)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->project = $project;
        $this->deploymentForm = $deploymentForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Project $project
     * @return Factory|View
     */
    public function index(Request $request, Project $project)
    {
        $page = $request->input('page', 1);
        $perPage = 10;
        $deployments = $project->getDeploymentsByPage($page, $perPage);
        return view('deployments.index')
            ->with('deployments', $deployments)
            ->with('project', $project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Project $project)
    {
        $input = array_merge($request->all(), [
            'status' => null,
            'message' => null,
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
        ]);

        if ($this->deploymentForm->save($input)) {
            $deployment = $project->getLastDeployment();
            $link = link_to_route('projects.deployments.show', "#$deployment->number", [$project, $deployment->number]);
            $request->session()->flash('status', "The deployment $link was successfully started.");

            return redirect()->route('projects.deployments.index', [$project]);
        } else {
            return redirect()->route('projects.deployments.index', [$project])
                ->withInput()
                ->withErrors($this->deploymentForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @param Deployment $deployment
     * @return Factory|View
     */
    public function show(Project $project, Deployment $deployment)
    {
        return view('deployments.show')->with('deployment', $deployment);
    }

    /**
     * Abort a running deploy.
     *
     * @param Project $project
     * @param Deployment $deployment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Project $project, Deployment $deployment)
    {
        $options = [
            'id' => $deployment->id,
            'status' => 2,
            'number' => $deployment->number,
            'message' => $deployment->message .= (($deployment->status === 3) ? 'Deploy Aborted' : 'Deploy Canceled'),
            'process_id' => $deployment->process_id
        ];

        if ($deployment->process_id !== null) {
            $process = new Process(['kill -9 ' . $deployment->process_id]);
            $process->run();
        }

        $project = $this->project->byId($deployment->project_id);

        if (($deployment->status === 3 || is_null($deployment->status)) && $project->updateDeployment($options)) {
            return redirect()->route('projects.deployments.index', [$project]);
        } else {
            return redirect()->route('projects.deployments.index', [$project])
                ->withInput()
                ->withErrors($this->deploymentForm->errors());
        }
    }
}
