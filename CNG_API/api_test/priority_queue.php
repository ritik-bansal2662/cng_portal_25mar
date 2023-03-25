<?php

$pq = new SplPriorityQueue();
$pq->insert(['ab','a'],1);
$pq->insert(['b','bc'],5);
$pq->insert(['cd','d'],33);
$pq->insert(['ab','a','d'],100);
$pq->insert(['b','bc','hg'],15);
$pq->insert(['cd','d','vf'],30);
// echo $pq->top();
// print_r($pq);

// Count the elements
echo "count ->" . $pq->count() . PHP_EOL;

// Sets the mode of extraction (EXTR_DATA, EXTR_PRIORITY, EXTR_BOTH)
$pq->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

// Go at the node from the top of the queue
$pq->top();

// Iterate the queue (by priority) and display each element
while ($pq->valid()) {
    echo "count ->" . $pq->count() . PHP_EOL;
    $curr = $pq->current();
    // print_r($pq->current());
    $data = $curr['data'];
    echo json_encode($data) , "\n";
    // echo $curr['priority'];
    echo PHP_EOL;
    $pq->next();
}

echo "count ->" . $pq->count() . PHP_EOL;
echo "\n-- -\n";
echo json_encode($data);

?>