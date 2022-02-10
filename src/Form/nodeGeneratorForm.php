<?php
/**
 * @file
 * Contains \Drupal\node_generator\Form\NodeGeneratorForm
 */
namespace Drupal\node_generator\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class NodeGeneratorForm extends FormBase{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'generator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node_types = node_type_get_names();
    $form['number_of_node']= array(
      '#type'=>'number',
      '#title'=> t('Enter Number of Nodes'),
      '#required'=>TRUE,
    );
    $form['node_type']= array(
      '#type'=>'select',
      '#title'=>'Content Type',
      '#options'=>$node_types,
      '#required'=>TRUE,
    );
    $form['submit']= array(
      '#type'=>'submit',
      '#value'=>'Generate',
      '#button_type'=>'primary',
    );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
    $number = $form_state->getValue('number_of_node');
    if($number < 2){
      $form_state->setErrorByName('number_of_node', $this->t('You have to generate atleast 2 nodes'));
      return;
    }else if($number >10){
      $form_state->setErrorByName('number_of_node', $this->t('You cannot generate more than 10 nodes'));
      return;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    /**
     * Generate Nodes
     */

    for($i = 0; $i<$form_state->getValue('number_of_node'); $i++){
      $node = Node::create(['type' => $form_state->getValue('node_type')]);
      $node->uid = 1;
      $node->promote = 0;
      $node->sticky = 0;
      $node->title= 'New';
      $node->save();
    }
    $this->messenger()->addMessage('Node Generated Successfully');
  }
}
